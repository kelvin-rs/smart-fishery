<?php

namespace App\Http\Controllers\Kud;

use App\Http\Controllers\Controller;
use App\Models\Kud;
use App\Models\Timbangan;
use App\Models\Tambak;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $prices = Kud::all();
        $totalTambak = Tambak::count();
        $totalPanenKg = Timbangan::sum('banyak_panen');
        $totalTransaksiRp = Timbangan::sum('total');
        $recentHarvests = Timbangan::orderBy('id', 'desc')->take(5)->get();

        return view('kud.dashboard', compact(
            'user',
            'prices',
            'totalTambak',
            'totalPanenKg',
            'totalTransaksiRp',
            'recentHarvests'
        ));
    }
}
