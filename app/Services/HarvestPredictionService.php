<?php

namespace App\Services;

use App\Repositories\Contracts\PrediksiRepositoryInterface;
use App\Repositories\Contracts\TambakRepositoryInterface;

class HarvestPredictionService
{
    protected PrediksiRepositoryInterface $prediksiRepo;
    protected TambakRepositoryInterface $tambakRepo;

    // Parameter Regresi Linier (a = Intersep, b = Slope) berdasarkan Tabel 3.26 - 3.30 Dokumen
    protected array $regressionModels = [
        'Bandeng' => [
            'Normal' => ['a' => 161.71, 'b' => 58.93, 'sr_min' => 0.80, 'sr_max' => 1.00],
            'Tidak Normal' => ['a' => 74.00, 'b' => 41.00, 'sr_min' => 0.50, 'sr_max' => 0.65],
        ],
        'Vaname' => [
            'Normal' => ['a' => 25.50, 'b' => 26.50, 'sr_min' => 0.90, 'sr_max' => 1.00],
            'Tidak Normal' => ['a' => 15.00, 'b' => 13.50, 'sr_min' => 0.50, 'sr_max' => 0.70],
        ],
        'Windu' => [
            'Normal' => ['a' => 22.00, 'b' => 21.00, 'sr_min' => 0.80, 'sr_max' => 1.00],
            'Tidak Normal' => ['a' => 14.50, 'b' => 10.50, 'sr_min' => 0.50, 'sr_max' => 0.60],
        ],
    ];

    public function __construct(
        PrediksiRepositoryInterface $prediksiRepo,
        TambakRepositoryInterface $tambakRepo
    ) {
        $this->prediksiRepo = $prediksiRepo;
        $this->tambakRepo = $tambakRepo;
    }

    /**
     * Hitung Prediksi Hasil Panen (Y = a + bx) dikalikan Survival Rate (SR)
     */
    public function predictHarvest(array $input): array
    {
        $idTambak = $input['id_tambak'] ?? 1;
        $jenisIkan = ucfirst(strtolower($input['jenis_ikan'] ?? 'Bandeng'));
        if (!isset($this->regressionModels[$jenisIkan])) {
            $jenisIkan = 'Bandeng';
        }

        $keadaan = ucfirst(strtolower($input['keadaan_tambak'] ?? 'Normal'));
        if (!in_array($keadaan, ['Normal', 'Tidak Normal'])) {
            $keadaan = 'Normal';
        }

        $bulan = (int) ($input['bulan'] ?? 5);
        if ($bulan < 1) $bulan = 1;

        $model = $this->regressionModels[$jenisIkan][$keadaan];

        // 1. Hitung Y = a + bx
        $yBase = $model['a'] + ($model['b'] * $bulan);

        // 2. Kalikan dengan faktor Survival Rate (SR)
        $prediksiMin = round($yBase * $model['sr_min'], 2);
        $prediksiMax = round($yBase * $model['sr_max'], 2);

        $teksPrediksi = "{$prediksiMin} - {$prediksiMax} Kg";

        // Simpan hasil ke database prediksi
        $saved = $this->prediksiRepo->create([
            'id_tambak' => $idTambak,
            'prediksi' => $teksPrediksi,
        ]);

        return [
            'status' => 'success',
            'jenis_ikan' => $jenisIkan,
            'keadaan_tambak' => $keadaan,
            'bulan' => $bulan,
            'konstanta_a' => $model['a'],
            'konstanta_b' => $model['b'],
            'y_base' => round($yBase, 2),
            'sr_range' => ($model['sr_min'] * 100) . '% - ' . ($model['sr_max'] * 100) . '%',
            'prediksi_min' => $prediksiMin,
            'prediksi_max' => $prediksiMax,
            'teks_prediksi' => $teksPrediksi,
            'record' => $saved,
        ];
    }
}
