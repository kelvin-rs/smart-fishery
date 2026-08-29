<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\Tambak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TambakController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tambaks = Tambak::all();

        return view('petambak.tambak.index', compact('user', 'tambaks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string|max:100',
            'banyak_benih' => 'required|integer|min:1',
            'jenis_ikan' => 'required|string|in:Bandeng,Vaname,Windu',
            'nomor' => 'required|integer|min:1',
        ], [
            'alamat.required' => 'Alamat lokasi tambak wajib diisi.',
            'banyak_benih.required' => 'Jumlah benih ikan wajib diisi.',
            'jenis_ikan.required' => 'Pilih jenis komoditas ikan/udang.',
            'nomor.required' => 'Nomor kolam/tambak wajib diisi.',
        ]);

        $tambak = Tambak::create([
            'alamat' => $request->alamat,
            'banyak_benih' => $request->banyak_benih,
            'jenis_ikan' => $request->jenis_ikan,
            'nomor' => $request->nomor,
        ]);

        // Hubungkan tambak ke user jika belum memiliki
        $user = Auth::user();
        if (!$user->id_tambak) {
            $user->id_tambak = $tambak->id;
            $user->save();
        }

        return redirect()->route('petambak.tambak.index')
            ->with('success', 'Data tambak baru berhasil didaftarkan ke sistem.');
    }
}
