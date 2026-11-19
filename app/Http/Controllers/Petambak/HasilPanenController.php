<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\Timbangan;
use App\Models\Kud;
use App\Models\Tambak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilPanenController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Timbangan::with('tambak')->where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('id_tambak', $user->id_tambak);
        });

        // Filter Pencarian (jenis ikan / banyak panen / total)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('jenis_ikan', 'like', "%{$search}%")
                  ->orWhere('banyak_panen', 'like', "%{$search}%");
            });
        }

        // Filter Tanggal Panen
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_panen', $request->tanggal);
        }

        // Filter Unit Tambak
        if ($request->filled('id_tambak')) {
            $query->where('id_tambak', $request->id_tambak);
        }

        // Filter Komoditas (opsional)
        if ($request->filled('jenis_ikan')) {
            $query->where('jenis_ikan', $request->jenis_ikan);
        }

        $harvests = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $kudPrices = Kud::all()->pluck('harga', 'jenis_ikan')->toArray();

        $tambaks = Tambak::where(function($q) use ($user) {
            if ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('id', $user->id_tambak);
            }
        })->get();

        if ($tambaks->isEmpty()) {
            $tambaks = Tambak::all();
        }

        return view('petambak.panen.index', compact('user', 'harvests', 'kudPrices', 'tambaks'));
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $harvest = Timbangan::where('id', $id)
            ->where(function($q) use ($user) {
                if ($user->role !== 'admin') {
                    $q->where('user_id', $user->id);
                }
            })
            ->firstOrFail();

        $harvest->delete();

        return redirect()->route('petambak.panen.index')
            ->with('success', 'Data hasil panen berhasil dihapus.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tambak' => 'required',
            'tanggal_panen' => 'required|date',
            'banyak_panen' => 'required|numeric|min:0.1',
        ]);

        $tambak = Tambak::find($request->id_tambak);
        $jenisIkan = $tambak->jenis_ikan ?? ($request->jenis_ikan ?? 'Bandeng');

        $hargaKg = Kud::where('jenis_ikan', $jenisIkan)->value('harga') ?? 20000;
        $total = (int) round(((float) $request->banyak_panen) * $hargaKg);

        Timbangan::create([
            'user_id' => Auth::id(),
            'id_tambak' => $request->id_tambak,
            'tanggal_panen' => $request->tanggal_panen,
            'banyak_panen' => (float) $request->banyak_panen,
            'jenis_ikan' => $jenisIkan,
            'total' => $total,
        ]);

        return redirect()->route('petambak.panen.index')
            ->with('success', 'Data panen timbangan berhasil ditambahkan.');
    }
}
