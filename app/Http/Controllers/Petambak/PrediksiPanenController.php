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

    public function index()
    {
        $user = Auth::user();
        $tambaks = Tambak::all();
        $latestKualitas = HasilNaive::orderBy('id', 'desc')->first();
        $statusKualitas = $latestKualitas->keterangan ?? 'Normal';
        $history = Prediksi::with('tambak')->orderBy('id_hasil', 'desc')->take(10)->get();

        return view('petambak.prediksi.index', compact('user', 'tambaks', 'statusKualitas', 'history'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'id_tambak' => 'required',
            'jenis_ikan' => 'required|in:Bandeng,Vaname,Windu',
            'bulan' => 'required|integer|min:1|max:12',
            'keadaan_tambak' => 'required|in:Normal,Tidak Normal',
        ]);

        $hasil = $this->predictionService->predictHarvest($request->all());

        return redirect()->route('petambak.prediksi.index')
            ->with('hasil_prediksi', $hasil)
            ->with('success', 'Estimasi prediksi panen berhasil dihitung.');
    }
}
