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

        if ($sensors->isEmpty()) {
            // Data sampel representatif jika database sensor awal masih sedikit
            $chartLabels = ['07:00 (Pagi)', '07:30', '08:00', '08:30', '11:00 (Siang)', '11:30', '12:00', '12:30', '15:00 (Sore)', '15:30', '16:00', '16:30'];
            $chartSuhu = [28.2, 28.5, 28.8, 29.0, 30.5, 31.0, 30.8, 30.2, 29.5, 29.2, 28.9, 28.6];
            $chartPh = [7.4, 7.5, 7.6, 7.8, 7.9, 7.8, 7.6, 7.5, 7.4, 7.3, 7.5, 7.4];
        } else {
            foreach ($sensors as $sensor) {
                $chartLabels[] = $sensor->waktu ? date('H:i d/m', strtotime($sensor->waktu)) : 'Data #' . $sensor->id;
                $chartSuhu[] = (float) $sensor->suhu;
                $chartPh[] = (float) $sensor->ph;
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
