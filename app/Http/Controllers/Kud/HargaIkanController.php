<?php

namespace App\Http\Controllers\Kud;

use App\Http\Controllers\Controller;
use App\Models\Kud;
use Illuminate\Http\Request;

class HargaIkanController extends Controller
{
    public function index()
    {
        $prices = Kud::all();
        return view('kud.harga.index', compact('prices'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'jenis_ikan' => 'required|string|in:Bandeng,Vaname,Windu',
            'harga' => 'required|integer|min:1000',
        ], [
            'jenis_ikan.required' => 'Pilih jenis komoditas.',
            'harga.required' => 'Masukkan nominal harga per Kg.',
            'harga.min' => 'Harga minimal Rp 1.000.',
        ]);

        Kud::updateOrCreate(
            ['jenis_ikan' => $request->jenis_ikan],
            ['harga' => $request->harga]
        );

        return redirect()->route('kud.harga.index')
            ->with('success', "Harga pasar untuk {$request->jenis_ikan} berhasil diperbarui menjadi Rp " . number_format($request->harga, 0, ',', '.') . " / Kg.");
    }
}
