<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\HasilNaive;
use App\Models\Prediksi;
use App\Models\Tambak;
use App\Services\HarvestPredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrediksiPanenController extends Controller
{
    protected HarvestPredictionService $predictionService;

    public function __construct(HarvestPredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $tambaks = Tambak::where(function($q) use ($user) {
                if ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('id', $user->id_tambak);
                }
            })
            ->get();

        if ($tambaks->isEmpty()) {
            $tambaks = Tambak::all();
        }

        // Kondisi mutu air berbasis VOTE MAYORITAS dari seluruh hasil Naïve Bayes per unit tambak
        $statusPerTambak = [];
        foreach ($tambaks as $t) {
            $matchTambak = function ($q) use ($t) {
                $q->where('id_tambak', $t->id);
                if (!empty($t->nomor)) {
                    $q->orWhere('id_tambak', $t->nomor);
                }
                if (($t->nomor ?? $t->id) == 1) {
                    $q->orWhereNull('id_tambak');
                }
            };

            $countNormal = HasilNaive::where($matchTambak)->where('keterangan', 'Normal')->count();
            $countTidak  = HasilNaive::where($matchTambak)->where('keterangan', '!=', 'Normal')->count();

            // Mayoritas menang; jika seri atau belum ada data → Normal
            $keadaan = $countTidak > $countNormal ? 'Tidak Normal' : 'Normal';

            $statusPerTambak[$t->id] = [
                'keadaan' => $keadaan,
                'normal'  => $countNormal,
                'tidak'   => $countTidak,
                'total'   => $countNormal + $countTidak,
            ];
        }

        $minDataNaive   = config('smartfishery.min_data_naive', 15);

        $firstTambakId  = $tambaks->first()->id ?? null;
        $statusKualitas = $statusPerTambak[$firstTambakId]['keadaan'] ?? 'Normal';
        $statusDetail   = '';

        $query = Prediksi::with('tambak')
            ->where(function($q) use ($user) {
                if ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereNull('user_id');
                }
            });

        // Filter Pencarian Universal (Komoditas, Tambak, Siklus, Kondisi, Bobot, Tanggal)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('prediksi', 'like', "%{$search}%")
                  ->orWhere('jenis_ikan', 'like', "%{$search}%")
                  ->orWhere('bulan', 'like', "%{$search}%")
                  ->orWhere('keadaan_tambak', 'like', "%{$search}%")
                  ->orWhere('tanggal', 'like', "%{$search}%")
                  ->orWhereHas('tambak', function($tq) use ($search) {
                      $tq->where('nomor', 'like', "%{$search}%")
                         ->orWhere('jenis_ikan', 'like', "%{$search}%")
                         ->orWhere('alamat', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $tgl = $request->tanggal;
            $query->where(function($q) use ($tgl) {
                $q->whereDate('tanggal', $tgl)
                  ->orWhereDate('created_at', $tgl);
            });
        }

        // Filter Komoditas
        if ($request->filled('jenis_ikan')) {
            $jenis = trim($request->jenis_ikan);
            $query->where(function($q) use ($jenis) {
                $q->where('jenis_ikan', 'like', "%{$jenis}%")
                  ->orWhere('prediksi', 'like', "%{$jenis}%")
                  ->orWhereHas('tambak', function($tq) use ($jenis) {
                      $tq->where('jenis_ikan', 'like', "%{$jenis}%");
                  });
            });
        }

        // Filter Kondisi Tambak
        if ($request->filled('keadaan')) {
            $query->where('keadaan_tambak', $request->keadaan);
        }

        $history = $query->orderBy('id_hasil', 'desc')->paginate(10)->withQueryString();

        return view('petambak.prediksi.index', compact('user', 'tambaks', 'statusKualitas', 'statusPerTambak', 'statusDetail', 'minDataNaive', 'history'));
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $record = Prediksi::where('id_hasil', $id)
            ->where(function($q) use ($user) {
                if ($user->role !== 'admin') {
                    $q->where('user_id', $user->id);
                }
            })
            ->firstOrFail();

        $record->delete();

        return redirect()->route('petambak.prediksi.index')
            ->with('success', 'Riwayat prediksi panen berhasil dihapus.');
    }

    public function proses(Request $request)
    {
        $request->validate([
            'tanggal' => 'nullable|date',
            'id_tambak' => 'required',
            'bulan' => 'required|integer|min:1|max:12',
            'luas_lahan' => 'nullable|numeric|min:1',
            'banyak_benih' => 'nullable|integer|min:1',
        ]);

        $idTambak = $request->id_tambak;
        $tambak = Tambak::find($idTambak);
        if (!$tambak) {
            return redirect()->route('petambak.prediksi.index')
                ->withInput()
                ->with('error', 'Unit tambak tidak valid atau tidak ditemukan.');
        }

        $matchTambak = function ($q) use ($tambak) {
            $q->where('id_tambak', $tambak->id);
            if (!empty($tambak->nomor)) {
                $q->orWhere('id_tambak', $tambak->nomor);
            }
            if (($tambak->nomor ?? $tambak->id) == 1) {
                $q->orWhereNull('id_tambak');
            }
        };

        // Peringatan minimal 15 data hasil Naïve Bayes untuk tambak ini
        $minData = config('smartfishery.min_data_naive', 15);
        $countNaive = HasilNaive::where($matchTambak)->count();
        if ($countNaive < $minData) {
            return redirect()->route('petambak.prediksi.index')
                ->withInput()
                ->with('error', "Tambak " . ($tambak->nomor ?? $tambak->id) . " baru memiliki {$countNaive} data hasil uji kualitas air. Dibutuhkan minimal {$minData} data Naïve Bayes untuk menjalankan prediksi panen.");
        }

        // Hitung vote mayoritas Naïve Bayes
        $countNormal = HasilNaive::where($matchTambak)->where('keterangan', 'Normal')->count();
        $countTidak  = HasilNaive::where($matchTambak)->where('keterangan', '!=', 'Normal')->count();
        $keadaanTambak = $countTidak > $countNormal ? 'Tidak Normal' : 'Normal';

        $params = $request->all();
        $params['user_id'] = Auth::id();
        $params['jenis_ikan'] = $tambak->jenis_ikan;
        $params['keadaan_tambak'] = $keadaanTambak;
        $params['luas_lahan'] = (float) ($request->luas_lahan ?: ($tambak->luas_lahan ?? 800));
        $params['banyak_benih'] = (int) ($request->banyak_benih ?: ($tambak->banyak_benih ?? 5000));

        try {
            $hasil = $this->predictionService->predictHarvest($params);

            return redirect()->route('petambak.prediksi.index')
                ->with('hasil_prediksi', $hasil)
                ->with('success', 'Estimasi prediksi panen berhasil dihitung oleh Server Machine Learning.');
        } catch (\Throwable $e) {
            return redirect()->route('petambak.prediksi.index')
                ->withInput()
                ->with('error', 'Gagal memproses prediksi panen via Server Machine Learning: ' . $e->getMessage() . '. Pastikan API di VPS sedang aktif.');
        }
    }
}
