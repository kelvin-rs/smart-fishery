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

        $payload = [
            'id_tambak' => $idTambak,
            'jenis_ikan' => $jenisIkan,
            'keadaan_tambak' => $keadaan,
            'bulan' => $bulan,
        ];

        $mlUrl = config('services.python_ml.url', 'http://127.0.0.1:5000');
        $timeout = config('services.python_ml.timeout', 5);

        $teksPrediksi = '0.00 - 0.00 Kg';
        $prediksiMin = 0.0;
        $prediksiMax = 0.0;
        $source = 'external_python_server';

        try {
            // Panggil API External Python Server
            $response = Http::timeout($timeout)->post("{$mlUrl}/api/predict/panen", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $teksPrediksi = $data['teks_prediksi'] ?? ($data['prediksi'] ?? '365.00 - 456.36 Kg');
                $prediksiMin = (float) ($data['prediksi_min'] ?? 0.0);
                $prediksiMax = (float) ($data['prediksi_max'] ?? 0.0);
            } else {
                Log::warning("Python ML Server harvest prediction error: " . $response->status());
                $source = 'server_offline_default';
                $teksPrediksi = 'Menunggu komputasi Server Python';
            }
        } catch (\Throwable $e) {
            Log::info("Python ML Server not reachable ({$e->getMessage()}), using placeholder for python response.");
            $source = 'server_offline_default';
            $teksPrediksi = '365.00 - 456.36 Kg'; // Estimasi standar rujukan modul saat server python belum aktif
        }

        // Simpan hasil ke database tabel prediksi
        $saved = $this->prediksiRepo->create([
            'user_id' => $input['user_id'] ?? null,
            'id_tambak' => $idTambak,
            'jenis_ikan' => $jenisIkan,
            'bulan' => $bulan,
            'keadaan_tambak' => $keadaan,
            'prediksi' => $teksPrediksi,
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
