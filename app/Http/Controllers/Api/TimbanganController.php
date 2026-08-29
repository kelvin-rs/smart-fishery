<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Timbangan;
use App\Models\Kud;
use Illuminate\Http\Request;

class TimbanganController extends Controller
{
    /**
     * Menerima payload data dari perangkat IoT timbangan panen digital
     * Sesuai Bab 3.5 Integrasi Sistem & Gambar 3.33
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tambak' => 'required',
            'banyak_panen' => 'required|numeric|min:0.1',
            'jenis_ikan' => 'required|string|in:Bandeng,Vaname,Windu',
            'tanggal_panen' => 'nullable|date',
        ]);

        $hargaKg = Kud::where('jenis_ikan', $validated['jenis_ikan'])->value('harga') ?? 20000;
        $total = (int) round($validated['banyak_panen'] * $hargaKg);

        $timbangan = Timbangan::create([
            'id_tambak' => $validated['id_tambak'],
            'tanggal_panen' => $validated['tanggal_panen'] ?? now()->toDateString(),
            'banyak_panen' => (int) $validated['banyak_panen'],
            'jenis_ikan' => $validated['jenis_ikan'],
            'total' => $total,
        ]);

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data timbangan berhasil direkam ke database.',
            'data_timbangan' => $timbangan,
            'harga_per_kg' => $hargaKg,
            'total_uang' => $total,
        ], 201);
    }
}
