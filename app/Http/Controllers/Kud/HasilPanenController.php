<?php

namespace App\Http\Controllers\Kud;

use App\Http\Controllers\Controller;
use App\Models\Timbangan;
use App\Models\Tambak;
use Illuminate\Http\Request;

class HasilPanenController extends Controller
{
    public function index()
    {
        $harvests = Timbangan::with('tambak')->orderBy('id', 'desc')->get();
        $totalTonase = Timbangan::sum('banyak_panen');
        $totalUang = Timbangan::sum('total');

        return view('kud.panen.index', compact('harvests', 'totalTonase', 'totalUang'));
    }
}
