<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\HasilNaive;
use App\Models\Tambak;
use App\Services\NaiveBayesService;
use Illuminate\Http\Request;

class KualitasAirController extends Controller
{
    protected NaiveBayesService $nbService;

    public function __construct(NaiveBayesService $nbService)
    {
        $this->nbService = $nbService;
    }

    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $query = HasilNaive::with('tambak')->where(function($q) use ($user) {
            if ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id');
            }
        });

        // Filter Pencarian Universal Cerdas (suhu / ph / keterangan / tambak / padat tebar / tanggal)
        if ($request->filled('search')) {
            $search = trim($request->search);

            // Ekstrak angka murni (misal: "Tambak 1" -> "1", "pH 7.7" -> "7.7", "28 °C" -> "28")
            $cleanNumeric = preg_replace('/[^\d\.]/', '', $search);
            $cleanTambakNo = preg_replace('/[^0-9]/', '', $search);

            // Deteksi format tanggal dd/mm/yyyy atau dd-mm-yyyy
            $dateSearch = null;
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $search, $dm)) {
                $dateSearch = sprintf('%04d-%02d-%02d', $dm[3], $dm[2], $dm[1]);
            }

            $query->where(function($q) use ($search, $cleanNumeric, $cleanTambakNo, $dateSearch) {
                $q->where('keterangan', 'like', "%{$search}%")
                  ->orWhere('ph', 'like', "%{$search}%")
                  ->orWhere('suhu', 'like', "%{$search}%")
                  ->orWhere('kesehatan', 'like', "%{$search}%")
                  ->orWhere('tanggal', 'like', "%{$search}%");

                if (!empty($dateSearch)) {
                    $q->orWhereDate('tanggal', $dateSearch)
                      ->orWhereDate('created_at', $dateSearch);
                }

                if (!empty($cleanNumeric)) {
                    $q->orWhere('ph', 'like', "%{$cleanNumeric}%")
                      ->orWhere('suhu', 'like', "%{$cleanNumeric}%");
                }

                if (!empty($cleanTambakNo)) {
                    $q->orWhere('id_tambak', $cleanTambakNo)
                      ->orWhereHas('tambak', function($tq) use ($cleanTambakNo) {
                          $tq->where('nomor', $cleanTambakNo);
                      });
                }

                $q->orWhereHas('tambak', function($tq) use ($search) {
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

        // Filter Unit Tambak
        if ($request->filled('id_tambak')) {
            $query->where('id_tambak', $request->id_tambak);
        }

        // Filter Status Lingkungan
        if ($request->filled('status')) {
            $query->where('keterangan', $request->status);
        }

        $history = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

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

        return view('petambak.kualitas-air.index', compact('history', 'tambaks'));
    }

    public function destroy($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $record = HasilNaive::where('id', $id)
            ->where(function($q) use ($user) {
                if ($user->role !== 'admin') {
                    $q->where('user_id', $user->id);
                }
            })
            ->firstOrFail();

        $record->delete();

        return redirect()->route('petambak.kualitas-air.index')
            ->with('success', 'Riwayat uji kualitas air berhasil dihapus.');
    }

    public function proses(Request $request)
    {
        $request->validate([
            'tanggal' => 'nullable|date',
            'id_tambak' => 'nullable|exists:tambak,id',
            'waktu' => 'required|in:Pagi,Siang,Sore',
            'suhu' => 'required|numeric|between:15,45',
            'ph' => 'required|numeric|between:3,12',
            'padat_tebar' => 'required|in:Normal,Tidak Normal',
            'jenis_ikan' => 'required|in:Bandeng,Vaname,Windu',
        ]);

        $params = $request->all();
        $params['user_id'] = \Illuminate\Support\Facades\Auth::id();

        try {
            $hasil = $this->nbService->predict($params);

            return redirect()->route('petambak.kualitas-air.index')
                ->with('hasil_uji', $hasil)
                ->with('success', 'Uji klasifikasi kualitas air berhasil diproses oleh Server Machine Learning.');
        } catch (\Throwable $e) {
            return redirect()->route('petambak.kualitas-air.index')
                ->withInput()
                ->with('error', 'Gagal memproses data via Server Machine Learning: ' . $e->getMessage() . '. Pastikan API di VPS sedang aktif.');
        }
    }
}
