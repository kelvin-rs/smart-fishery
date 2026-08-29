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

    public function index()
    {
        $history = HasilNaive::orderBy('id', 'desc')->take(10)->get();
        $tambaks = Tambak::all();

        return view('petambak.kualitas-air.index', compact('history', 'tambaks'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'waktu' => 'required|in:Pagi,Siang,Sore',
            'suhu' => 'required|numeric|between:15,45',
            'ph' => 'required|numeric|between:3,12',
            'padat_tebar' => 'required|in:Normal,Tidak Normal',
            'jenis_ikan' => 'required|in:Bandeng,Vaname,Windu',
        ]);

        $hasil = $this->nbService->predict($request->all());

        return redirect()->route('petambak.kualitas-air.index')
            ->with('hasil_uji', $hasil)
            ->with('success', 'Uji klasifikasi kualitas air berhasil diproses.');
    }
}
