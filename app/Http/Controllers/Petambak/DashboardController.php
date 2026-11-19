<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\Tambak;
use App\Models\Kud;
use App\Models\HasilNaive;
use App\Models\Prediksi;
use App\Models\Ikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil tambak aktif user atau default tambak pertama
        $tambakList = Tambak::all();
        $selectedTambakId = $request->query('tambak_id', $user->id_tambak ?? ($tambakList->first()->id ?? null));
        $tambak = Tambak::find($selectedTambakId) ?? $tambakList->first();

        $jenisIkan = $tambak->jenis_ikan ?? 'Bandeng';
        
        // Ambil harga terkini dari KUD
        $kudPrice = Kud::where('jenis_ikan', $jenisIkan)->first();
        $hargaIkan = $kudPrice->harga ?? 20000;

        // Closure filter HasilNaive untuk tambak terpilih (termasuk data lama yang id_tambak-nya NULL pada Tambak 1)
        $matchTambakNaive = function ($q) use ($tambak) {
            if (!$tambak) {
                return;
            }
            $q->where(function ($sub) use ($tambak) {
                $sub->where('id_tambak', $tambak->id);
                if (!empty($tambak->nomor)) {
                    $sub->orWhere('id_tambak', $tambak->nomor);
                }
                if (($tambak->nomor ?? $tambak->id) == 1) {
                    $sub->orWhereNull('id_tambak');
                }
            });
        };

        // Ambil status kualitas air berdasarkan vote mayoritas Naïve Bayes pada tambak terpilih
        $countNormal = $tambak ? HasilNaive::where($matchTambakNaive)->where('keterangan', 'Normal')->count() : 0;
        $countTidak  = $tambak ? HasilNaive::where($matchTambakNaive)->where('keterangan', '!=', 'Normal')->count() : 0;
        if (($countNormal + $countTidak) > 0) {
            $statusTambak = $countTidak > $countNormal ? 'Tidak Normal' : 'Normal';
        } else {
            $latestKualitas = HasilNaive::orderBy('id', 'desc')->first();
            $statusTambak = $latestKualitas->keterangan ?? 'Normal';
        }

        // Ambil prediksi panen terakhir
        $latestPrediksi = Prediksi::where('id_tambak', $tambak->id ?? 1)->orderBy('id_hasil', 'desc')->first();
        $prediksiPanen = $latestPrediksi->prediksi ?? '365.00 - 456.36 Kg';

        // Ambil hingga 15 data terakhir untuk grafik pH dan Suhu
        // Prioritas utama: tabel HasilNaive (data menu Cek Kualitas Air) agar 100% sinkron dengan tabel kualitas air
        $filterDate = $request->query('tanggal');

        $naiveQuery  = HasilNaive::orderBy('id', 'desc');
        $sensorQuery = Ikan::orderBy('id', 'desc');

        if ($tambak) {
            $naiveQuery->where($matchTambakNaive);
            $sensorQuery->where('id_tambak', $tambak->id);
        }
        if ($filterDate) {
            $naiveQuery->where(function ($q) use ($filterDate) {
                $q->whereDate('tanggal', $filterDate)
                  ->orWhereDate('created_at', $filterDate);
            });
            $sensorQuery->whereDate('waktu', $filterDate);
        }

        $naiveData = $naiveQuery->take(15)->get()->reverse()->values();
        $sensors   = $sensorQuery->take(15)->get()->reverse()->values();

        // Siapkan data label & nilai untuk Chart.js
        $chartLabels = [];
        $chartSuhu   = [];
        $chartPh     = [];

        if ($naiveData->isNotEmpty()) {
            foreach ($naiveData as $item) {
                $waktuFormat = $item->created_at
                    ? $item->created_at->format('H:i d/m')
                    : ($item->tanggal ? date('d/m', strtotime($item->tanggal)) : 'Uji #' . $item->id);
                $chartLabels[] = $waktuFormat;
                $chartSuhu[]   = (float) $item->suhu;
                $chartPh[]     = (float) $item->ph;
            }
        } elseif ($sensors->isNotEmpty()) {
            foreach ($sensors as $sensor) {
                $chartLabels[] = $sensor->waktu ? date('H:i d/m', strtotime($sensor->waktu)) : 'Data #' . $sensor->id;
                $chartSuhu[]   = (float) $sensor->suhu;
                $chartPh[]     = (float) $sensor->ph;
            }
        }

        // Jika baru ada 1 titik data (< 2 titik), tambahkan titik awal agar Chart.js tetap menggambar garis grafik
        if (count($chartLabels) === 1) {
            array_unshift($chartLabels, 'Awal');
            array_unshift($chartSuhu, $chartSuhu[0]);
            array_unshift($chartPh, $chartPh[0]);
        }

        return view('petambak.dashboard', compact(
            'user',
            'tambak',
            'tambakList',
            'jenisIkan',
            'hargaIkan',
            'statusTambak',
            'prediksiPanen',
            'chartLabels',
            'chartSuhu',
            'chartPh',
            'filterDate'
        ));
    }
}
