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

        // Ambil status kualitas air berdasarkan vote mayoritas Naïve Bayes pada tambak terpilih
        $countNormal = $tambak ? HasilNaive::where('id_tambak', $tambak->id)->where('keterangan', 'Normal')->count() : 0;
        $countTidak  = $tambak ? HasilNaive::where('id_tambak', $tambak->id)->where('keterangan', '!=', 'Normal')->count() : 0;
        if (($countNormal + $countTidak) > 0) {
            $statusTambak = $countTidak > $countNormal ? 'Tidak Normal' : 'Normal';
        } else {
            $latestKualitas = HasilNaive::orderBy('id', 'desc')->first();
            $statusTambak = $latestKualitas->keterangan ?? 'Normal';
        }

        // Ambil prediksi panen terakhir
        $latestPrediksi = Prediksi::where('id_tambak', $tambak->id ?? 1)->orderBy('id_hasil', 'desc')->first();
        $prediksiPanen = $latestPrediksi->prediksi ?? '365.00 - 456.36 Kg';

        // Ambil hingga 15 data terakhir untuk grafik pH dan Suhu (tetap tampil walau data < 15 atau status Tidak Normal)
        $filterDate = $request->query('tanggal');

        $sensorQuery = Ikan::orderBy('id', 'desc');
        $naiveQuery  = HasilNaive::orderBy('id', 'desc');

        if ($tambak) {
            $sensorQuery->where('id_tambak', $tambak->id);
            $naiveQuery->where('id_tambak', $tambak->id);
        }
        if ($filterDate) {
            $sensorQuery->whereDate('waktu', $filterDate);
            $naiveQuery->whereDate('tanggal', $filterDate);
        }

        $sensors   = $sensorQuery->take(15)->get()->reverse()->values();
        $naiveData = $naiveQuery->take(15)->get()->reverse()->values();

        // Siapkan data label & nilai untuk Chart.js
        $chartLabels = [];
        $chartSuhu   = [];
        $chartPh     = [];

        // Gunakan sumber data tambak yang memiliki titik data lebih lengkap (HasilNaive vs Ikan)
        if ($naiveData->count() >= $sensors->count() && $naiveData->isNotEmpty()) {
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
        } else {
            // Fallback jika tambak baru belum punya data sama sekali: tampilkan 15 data uji terakhir di sistem
            $fallbackNaive = HasilNaive::orderBy('id', 'desc')->take(15)->get()->reverse()->values();
            if ($fallbackNaive->isNotEmpty()) {
                foreach ($fallbackNaive as $item) {
                    $waktuFormat = $item->created_at
                        ? $item->created_at->format('H:i d/m')
                        : ($item->tanggal ? date('d/m', strtotime($item->tanggal)) : 'Uji #' . $item->id);
                    $chartLabels[] = $waktuFormat;
                    $chartSuhu[]   = (float) $item->suhu;
                    $chartPh[]     = (float) $item->ph;
                }
            } else {
                $fallbackSensors = Ikan::orderBy('id', 'desc')->take(15)->get()->reverse()->values();
                foreach ($fallbackSensors as $sensor) {
                    $chartLabels[] = $sensor->waktu ? date('H:i d/m', strtotime($sensor->waktu)) : 'Data #' . $sensor->id;
                    $chartSuhu[]   = (float) $sensor->suhu;
                    $chartPh[]     = (float) $sensor->ph;
                }
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
