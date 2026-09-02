<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\Timbangan;
use App\Models\Kud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilPanenController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Timbangan::where(function($q) use ($user) {
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

        // Filter Komoditas
        if ($request->filled('jenis_ikan')) {
            $query->where('jenis_ikan', $request->jenis_ikan);
        }

        $harvests = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $kudPrices = Kud::all()->pluck('harga', 'jenis_ikan')->toArray();

        return view('petambak.panen.index', compact('user', 'harvests', 'kudPrices'));
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
