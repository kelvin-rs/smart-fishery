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

        // Ambil status kualitas air terakhir
        $latestKualitas = HasilNaive::orderBy('id', 'desc')->first();
        $statusTambak = $latestKualitas->keterangan ?? 'Normal';

        // Ambil prediksi panen terakhir
        $latestPrediksi = Prediksi::where('id_tambak', $tambak->id ?? 1)->orderBy('id_hasil', 'desc')->first();
        $prediksiPanen = $latestPrediksi->prediksi ?? '365.00 - 456.36 Kg';

        // Ambil 15 data sensor terakhir untuk grafik pH dan Suhu
        $filterDate = $request->query('tanggal');
        $sensorQuery = Ikan::orderBy('id', 'asc');
        
        if ($tambak) {
            $sensorQuery->where('id_tambak', $tambak->id);
        }
        if ($filterDate) {
            $sensorQuery->whereDate('waktu', $filterDate);
        }
        
        $sensors = $sensorQuery->take(15)->get();

        // Siapkan data label & nilai untuk Chart.js
        $chartLabels = [];
        $chartSuhu = [];
        $chartPh = [];

        if ($sensors->isNotEmpty()) {
            foreach ($sensors as $sensor) {
                $chartLabels[] = $sensor->waktu ? date('H:i d/m', strtotime($sensor->waktu)) : 'Data #' . $sensor->id;
                $chartSuhu[] = (float) $sensor->suhu;
                $chartPh[] = (float) $sensor->ph;
            }
        } else {
            // Fallback: Ambil data dari riwayat uji kualitas air (HasilNaive) jika data sensor IoT belum ada
            $naiveQuery = HasilNaive::orderBy('id', 'asc');
            if ($tambak) {
                $naiveQuery->where('id_tambak', $tambak->id);
            }
            if ($filterDate) {
                $naiveQuery->whereDate('tanggal', $filterDate);
            }
            $naiveData = $naiveQuery->take(15)->get();

            // Jika untuk tambak ini belum ada, coba cari berdasarkan user aktif
            if ($naiveData->isEmpty() && $user) {
                $naiveData = HasilNaive::where('user_id', $user->id)
                    ->orderBy('id', 'asc')
                    ->take(15)
                    ->get();
            }

            // Jika masih kosong (misal data umum), ambil 15 data uji terakhir yang ada di database
            if ($naiveData->isEmpty()) {
                $naiveData = HasilNaive::orderBy('id', 'asc')->take(15)->get();
            }

            foreach ($naiveData as $item) {
                $waktuFormat = $item->created_at 
                    ? $item->created_at->format('H:i d/m') 
                    : ($item->tanggal ? date('d/m', strtotime($item->tanggal)) : 'Uji #' . $item->id);
                $chartLabels[] = $waktuFormat;
                $chartSuhu[] = (float) $item->suhu;
                $chartPh[] = (float) $item->ph;
            }
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
