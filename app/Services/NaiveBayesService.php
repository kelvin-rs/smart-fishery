<?php

namespace App\Services;

use App\Repositories\Contracts\DataTrainRepositoryInterface;
use App\Repositories\Contracts\HasilNaiveRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NaiveBayesService
{
    protected DataTrainRepositoryInterface $dataTrainRepo;
    protected HasilNaiveRepositoryInterface $hasilNaiveRepo;

    public function __construct(
        DataTrainRepositoryInterface $dataTrainRepo,
        HasilNaiveRepositoryInterface $hasilNaiveRepo
    ) {
        $this->dataTrainRepo = $dataTrainRepo;
        $this->hasilNaiveRepo = $hasilNaiveRepo;
    }

    /**
     * Mengirim data parameter air ke External Python ML Server
     * untuk diklasifikasikan menggunakan Gaussian Naive Bayes di Python.
     * Tidak ada perhitungan matematika / algoritma lokal di Laravel.
     */
    public function predict(array $input): array
    {
        $waktu = ucfirst(strtolower($input['waktu'] ?? 'Pagi'));
        $suhu = (float) ($input['suhu'] ?? 28.0);
        $ph = (float) ($input['ph'] ?? 7.5);
        $padatTebar = ucfirst(strtolower($input['padat_tebar'] ?? 'Normal'));
        $jenisIkan = ucfirst(strtolower($input['jenis_ikan'] ?? 'Bandeng'));

        $payload = [
            'waktu' => $waktu,
            'suhu' => $suhu,
            'ph' => $ph,
            'padat_tebar' => $padatTebar,
            'jenis_ikan' => $jenisIkan,
        ];

        $mlUrl = config('services.python_ml.url', 'http://127.0.0.1:5000');
        $timeout = config('services.python_ml.timeout', 5);

        $hasilPrediksi = 'Normal';
        $posteriorNormal = 0.0;
        $posteriorTidak = 0.0;
        $source = 'external_python_server';

        $response = Http::timeout($timeout)->post("{$mlUrl}/api/predict/kualitas-air", $payload);

        if (!$response->successful()) {
            $errBody = $response->json('message') ?? $response->body();
            Log::error("Python ML Server Naive Bayes error [{$response->status()}]: {$errBody}");
            throw new \RuntimeException("Server Machine Learning di VPS mengembalikan error: {$errBody}");
        }

        $data = $response->json();
        $hasilPrediksi = $data['hasil_prediksi'] ?? ($data['keterangan'] ?? 'Normal');
        $posteriorNormal = (float) ($data['posterior_normal'] ?? 0.0);
        $posteriorTidak = (float) ($data['posterior_tidak'] ?? 0.0);

        // Simpan log ke database tabel hasil_naive
        $saved = $this->hasilNaiveRepo->create([
            'user_id' => $input['user_id'] ?? null,
            'id_tambak' => $input['id_tambak'] ?? null,
            'tanggal' => $input['tanggal'] ?? date('Y-m-d'),
            'keterangan' => $hasilPrediksi,
            'ph' => (string) $ph,
            'suhu' => (string) $suhu,
            'kesehatan' => $padatTebar,
            'hasil_normal' => (string) $posteriorNormal,
            'hasil_tidak' => (string) $posteriorTidak,
            'created_at' => isset($input['tanggal']) ? ($input['tanggal'] . ' ' . date('H:i:s')) : now(),
        ]);

        return [
            'status' => 'success',
            'source' => $source,
            'waktu' => $waktu,
            'suhu' => $suhu,
            'ph' => $ph,
            'padat_tebar' => $padatTebar,
            'jenis_ikan' => $jenisIkan,
            'hasil_prediksi' => $hasilPrediksi,
            'posterior_normal' => $posteriorNormal,
            'posterior_tidak' => $posteriorTidak,
            'record' => $saved,
        ];
    }
}
