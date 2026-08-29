<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ikan;
use App\Models\Tambak;
use App\Models\HasilNaive;
use App\Services\NaiveBayesService;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    protected NaiveBayesService $nbService;

    public function __construct(NaiveBayesService $nbService)
    {
        $this->nbService = $nbService;
    }

    /**
     * Menerima payload data pembacaan sensor IoT (Suhu & pH) dari perangkat Smart Fishery
     * Sesuai Bab 3.5 Integrasi Sistem & Tabel 4.3
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tambak' => 'required',
            'ph' => 'required|numeric',
            'suhu' => 'required|numeric',
            'jenis_ikan' => 'nullable|string',
            'waktu' => 'nullable|date',
        ]);

        $tambak = Tambak::find($validated['id_tambak']);
        $jenisIkan = $validated['jenis_ikan'] ?? ($tambak->jenis_ikan ?? 'Bandeng');
        $waktu = $validated['waktu'] ?? now();

        // 1. Simpan data sensor ke tabel ikan
        $sensor = Ikan::create([
            'id_tambak' => $validated['id_tambak'],
            'waktu' => $waktu,
            'ph' => $validated['ph'],
            'suhu' => $validated['suhu'],
            'jenis_ikan' => $jenisIkan,
        ]);

        // 2. Tentukan slot waktu berdasarkan jam saat ini
        $hour = (int) date('H', strtotime($waktu));
        $slotWaktu = 'Pagi';
        if ($hour >= 11 && $hour < 15) {
            $slotWaktu = 'Siang';
        } elseif ($hour >= 15) {
            $slotWaktu = 'Sore';
        }

        // 3. Klasifikasi otomatis dengan Gaussian Naive Bayes
        $klasifikasi = $this->nbService->predict([
            'waktu' => $slotWaktu,
            'suhu' => $validated['suhu'],
            'ph' => $validated['ph'],
            'padat_tebar' => 'Normal',
            'jenis_ikan' => $jenisIkan,
        ]);

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data sensor berhasil diterima dan diklasifikasi oleh sistem.',
            'data_sensor' => $sensor,
            'status_tambak' => $klasifikasi['hasil_prediksi'],
            'posterior_normal' => $klasifikasi['posterior_normal'],
            'posterior_tidak' => $klasifikasi['posterior_tidak'],
        ], 201);
    }

    /**
     * Endpoint API untuk polling data real-time chart di Dashboard Petambak
     */
    public function getRealtimeChart(Request $request, $idTambak)
    {
        $sensors = Ikan::where('id_tambak', $idTambak)
            ->orderBy('id', 'desc')
            ->take(15)
            ->get()
            ->reverse()
            ->values();

        $labels = [];
        $suhu = [];
        $ph = [];

        foreach ($sensors as $s) {
            $labels[] = $s->waktu ? date('H:i:s', strtotime($s->waktu)) : '#' . $s->id;
            $suhu[] = (float) $s->suhu;
            $ph[] = (float) $s->ph;
        }

        $latestKualitas = HasilNaive::orderBy('id', 'desc')->first();

        return response()->json([
            'labels' => $labels,
            'suhu' => $suhu,
            'ph' => $ph,
            'status_tambak' => $latestKualitas->keterangan ?? 'Normal',
        ]);
    }
}
