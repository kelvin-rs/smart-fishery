<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\Timbangan;
use App\Models\Kud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilPanenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $harvests = Timbangan::where('user_id', $user->id)
            ->orWhere('id_tambak', $user->id_tambak)
            ->orderBy('id', 'desc')
            ->get();
        $kudPrices = Kud::all()->pluck('harga', 'jenis_ikan')->toArray();

        return view('petambak.panen.index', compact('user', 'harvests', 'kudPrices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tambak' => 'required',
            'tanggal_panen' => 'required|date',
            'banyak_panen' => 'required|numeric|min:1',
            'jenis_ikan' => 'required|in:Bandeng,Vaname,Windu',
        ]);

        $hargaKg = Kud::where('jenis_ikan', $request->jenis_ikan)->value('harga') ?? 20000;
        $total = (int) round($request->banyak_panen * $hargaKg);

        Timbangan::create([
            'user_id' => Auth::id(),
            'id_tambak' => $request->id_tambak,
            'tanggal_panen' => $request->tanggal_panen,
            'banyak_panen' => (int) $request->banyak_panen,
            'jenis_ikan' => $request->jenis_ikan,
            'total' => $total,
        ]);

        return redirect()->route('petambak.panen.index')
            ->with('success', 'Data panen timbangan berhasil ditambahkan.');
    }
}
