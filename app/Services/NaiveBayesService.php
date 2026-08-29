<?php

namespace App\Services;

use App\Repositories\Contracts\DataTrainRepositoryInterface;
use App\Repositories\Contracts\HasilNaiveRepositoryInterface;

class NaiveBayesService
{
    protected DataTrainRepositoryInterface $dataTrainRepo;
    protected HasilNaiveRepositoryInterface $hasilNaiveRepo;

    // Nilai Rata-rata (Mean) dari data latih ilmiah (Tabel 3.5 Dokumen)
    protected array $means = [
        'Pagi' => [
            'Normal' => ['suhu' => 28.481, 'ph' => 7.328],
            'Tidak Normal' => ['suhu' => 28.262, 'ph' => 7.608],
        ],
        'Siang' => [
            'Normal' => ['suhu' => 30.429, 'ph' => 7.566],
            'Tidak Normal' => ['suhu' => 29.429, 'ph' => 7.756],
        ],
        'Sore' => [
            'Normal' => ['suhu' => 30.114, 'ph' => 7.319],
            'Tidak Normal' => ['suhu' => 28.824, 'ph' => 7.961],
        ],
    ];

    // Nilai Standar Deviasi (Sigma) dari data latih ilmiah (Tabel 3.18 Dokumen)
    protected array $stds = [
        'Pagi' => [
            'Normal' => ['suhu' => 0.769, 'ph' => 0.392],
            'Tidak Normal' => ['suhu' => 3.576, 'ph' => 1.736],
        ],
        'Siang' => [
            'Normal' => ['suhu' => 1.551, 'ph' => 0.480],
            'Tidak Normal' => ['suhu' => 3.526, 'ph' => 1.347],
        ],
        'Sore' => [
            'Normal' => ['suhu' => 0.510, 'ph' => 0.299],
            'Tidak Normal' => ['suhu' => 3.298, 'ph' => 1.211],
        ],
    ];

    // Probabilitas Prior (Tabel 3.19 Dokumen)
    protected array $priors = [
        'Normal' => 0.504,
        'Tidak Normal' => 0.496,
    ];

    public function __construct(
        DataTrainRepositoryInterface $dataTrainRepo,
        HasilNaiveRepositoryInterface $hasilNaiveRepo
    ) {
        $this->dataTrainRepo = $dataTrainRepo;
        $this->hasilNaiveRepo = $hasilNaiveRepo;
    }

    /**
     * Hitung probabilitas Gaussian density untuk data kontinu
     * P(x | H) = (1 / (sqrt(2*pi)*sigma)) * exp(-((x - mu)^2) / (2 * sigma^2))
     */
    private function calculateGaussian(float $x, float $mean, float $std): float
    {
        if ($std <= 0) $std = 0.0001;
        $exponent = -pow($x - $mean, 2) / (2 * pow($std, 2));
        return (1 / (sqrt(2 * M_PI) * $std)) * exp($exponent);
    }

    /**
     * Prediksi status kualitas air tambak (Normal / Tidak Normal)
     */
    public function predict(array $input): array
    {
        $waktu = ucfirst(strtolower($input['waktu'] ?? 'Pagi'));
        if (!in_array($waktu, ['Pagi', 'Siang', 'Sore'])) {
            $waktu = 'Pagi';
        }

        $suhu = (float) ($input['suhu'] ?? 28.0);
        $ph = (float) ($input['ph'] ?? 7.5);
        $padatTebar = ucfirst(strtolower($input['padat_tebar'] ?? 'Normal'));
        $jenisIkan = ucfirst(strtolower($input['jenis_ikan'] ?? 'Bandeng'));

        // 1. Hitung Gaussian Suhu & pH untuk Kelas Normal
        $gaussSuhuNormal = $this->calculateGaussian($suhu, $this->means[$waktu]['Normal']['suhu'], $this->stds[$waktu]['Normal']['suhu']);
        $gaussPhNormal = $this->calculateGaussian($ph, $this->means[$waktu]['Normal']['ph'], $this->stds[$waktu]['Normal']['ph']);

        // 2. Hitung Gaussian Suhu & pH untuk Kelas Tidak Normal
        $gaussSuhuTidak = $this->calculateGaussian($suhu, $this->means[$waktu]['Tidak Normal']['suhu'], $this->stds[$waktu]['Tidak Normal']['suhu']);
        $gaussPhTidak = $this->calculateGaussian($ph, $this->means[$waktu]['Tidak Normal']['ph'], $this->stds[$waktu]['Tidak Normal']['ph']);

        // 3. Probabilitas Diskrit Padat Tebar (Tabel 3.20)
        $probPadatNormal = ($padatTebar === 'Normal') ? 1.000 : 0.500;
        $probPadatTidak = ($padatTebar === 'Normal') ? 0.500 : 0.500;

        // 4. Probabilitas Diskrit Jenis Ikan (Tabel 3.21)
        $probIkanNormal = 0.333;
        $probIkanTidak = 0.333;

        // 5. Posterior Likelihood
        $posteriorNormal = $gaussSuhuNormal * $gaussPhNormal * $probPadatNormal * $probIkanNormal * $this->priors['Normal'];
        $posteriorTidak = $gaussSuhuTidak * $gaussPhTidak * $probPadatTidak * $probIkanTidak * $this->priors['Tidak Normal'];

        // Tentukan hasil prediksi dengan membandingkan nilai posterior
        $hasilPrediksi = ($posteriorNormal >= $posteriorTidak) ? 'Normal' : 'Tidak Normal';

        // Simpan log ke database hasil_naive
        $saved = $this->hasilNaiveRepo->create([
            'keterangan' => $hasilPrediksi,
            'ph' => (string) $ph,
            'suhu' => (string) $suhu,
            'kesehatan' => $padatTebar,
            'hasil_normal' => sprintf("%.6e", $posteriorNormal),
            'hasil_tidak' => sprintf("%.6e", $posteriorTidak),
        ]);

        return [
            'status' => 'success',
            'waktu' => $waktu,
            'suhu' => $suhu,
            'ph' => $ph,
            'padat_tebar' => $padatTebar,
            'jenis_ikan' => $jenisIkan,
            'hasil_prediksi' => $hasilPrediksi,
            'posterior_normal' => $posteriorNormal,
            'posterior_tidak' => $posteriorTidak,
            'gauss_suhu_normal' => $gaussSuhuNormal,
            'gauss_ph_normal' => $gaussPhNormal,
            'gauss_suhu_tidak' => $gaussSuhuTidak,
            'gauss_ph_tidak' => $gaussPhTidak,
            'record' => $saved,
        ];
    }
}
