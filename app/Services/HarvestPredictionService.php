<?php

namespace App\Services;

use App\Repositories\Contracts\PrediksiRepositoryInterface;
use App\Repositories\Contracts\TambakRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HarvestPredictionService
{
    protected PrediksiRepositoryInterface $prediksiRepo;
    protected TambakRepositoryInterface $tambakRepo;

    public function __construct(
        PrediksiRepositoryInterface $prediksiRepo,
        TambakRepositoryInterface $tambakRepo
    ) {
        $this->prediksiRepo = $prediksiRepo;
        $this->tambakRepo = $tambakRepo;
    }

    /**
     * Mengirim data siklus panen ke External Python ML Server
     * untuk dihitung menggunakan model Regresi Linier & Survival Rate di Python.
     * Tidak ada perhitungan matematika / rumus lokal di Laravel.
     */
    public function predictHarvest(array $input): array
    {
        $idTambak = $input['id_tambak'] ?? 1;
        $jenisIkan = ucfirst(strtolower($input['jenis_ikan'] ?? 'Bandeng'));
        $keadaan = ucfirst(strtolower($input['keadaan_tambak'] ?? 'Normal'));
        $bulan = (int) ($input['bulan'] ?? 5);

        // Ambil data lahan dan benih dari tambak atau input request
        $tambak = null;
        try {
            $tambak = $this->tambakRepo->findById((int) $idTambak) ?? \App\Models\Tambak::find($idTambak);
        } catch (\Throwable $e) {
            $tambak = \App\Models\Tambak::find($idTambak);
        }

        $luasLahan = (float) ($input['luas_lahan'] ?? ($tambak->luas_lahan ?? 800.0));
        $banyakBenih = (int) ($input['banyak_benih'] ?? ($tambak->banyak_benih ?? 5000));

        $payload = [
            'id_tambak' => $idTambak,
            'jenis_ikan' => $jenisIkan,
            'keadaan_tambak' => $keadaan,
            'bulan' => $bulan,
            'bulan_ke' => $bulan,
            'luas_lahan_m2' => $luasLahan,
            'banyak_benih' => $banyakBenih,
        ];

        $mlUrl = config('services.python_ml.url', 'http://127.0.0.1:5000');
        $timeout = config('services.python_ml.timeout', 5);

        $teksPrediksi = '0.00 - 0.00 Kg';
        $prediksiMin = 0.0;
        $prediksiMax = 0.0;
        $source = 'external_python_server';

        $response = Http::timeout($timeout)->post("{$mlUrl}/api/predict/panen", $payload);

        if (!$response->successful()) {
            $errBody = $response->json('message') ?? $response->body();
            Log::error("Python ML Server harvest error [{$response->status()}]: {$errBody}");
            throw new \RuntimeException("Server Machine Learning di VPS mengembalikan error: {$errBody}");
        }

        $data = $response->json();
        $teksPrediksi = $data['teks_prediksi'] ?? ($data['prediksi'] ?? (isset($data['prediksi_kg']) ? number_format($data['prediksi_kg'], 2) . ' Kg' : '0.00 Kg'));
        $prediksiMin = (float) ($data['prediksi_min'] ?? ($data['prediksi_kg'] ?? 0.0));
        $prediksiMax = (float) ($data['prediksi_max'] ?? ($data['prediksi_kg'] ?? 0.0));

        // Simpan hasil ke database tabel prediksi
        $saved = $this->prediksiRepo->create([
            'user_id' => $input['user_id'] ?? null,
            'id_tambak' => $idTambak,
            'tanggal' => $input['tanggal'] ?? date('Y-m-d'),
            'jenis_ikan' => $jenisIkan,
            'bulan' => $bulan,
            'keadaan_tambak' => $keadaan,
            'prediksi' => $teksPrediksi,
            'created_at' => isset($input['tanggal']) ? ($input['tanggal'] . ' ' . date('H:i:s')) : now(),
        ]);

        return [
            'status' => 'success',
            'source' => $source,
            'id_tambak' => $idTambak,
            'jenis_ikan' => $jenisIkan,
            'keadaan_tambak' => $keadaan,
            'bulan' => $bulan,
            'prediksi_min' => $prediksiMin,
            'prediksi_max' => $prediksiMax,
            'teks_prediksi' => $teksPrediksi,
            'record' => $saved,
        ];
    }
}
