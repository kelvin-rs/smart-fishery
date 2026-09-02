<?php

namespace App\Http\Controllers\Kud;

use App\Http\Controllers\Controller;
use App\Models\Timbangan;
use App\Models\Tambak;
use Illuminate\Http\Request;

class HasilPanenController extends Controller
{
    public function index(Request $request)
    {
        $query = Timbangan::with(['tambak.user']);

        // Filter Pencarian (komoditas / tambak)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('jenis_ikan', 'like', "%{$search}%")
                  ->orWhere('id_tambak', 'like', "%{$search}%")
                  ->orWhereHas('tambak.user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                  });
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
        $totalTonase = Timbangan::sum('banyak_panen');
        $totalUang = Timbangan::sum('total');

        return view('kud.panen.index', compact('harvests', 'totalTonase', 'totalUang'));
    }
}
