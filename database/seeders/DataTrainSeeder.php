<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataTrain;
use App\Models\HasilNaive;
use App\Models\Prediksi;
use App\Models\Tambak;
use App\Models\Ikan;
use Illuminate\Support\Facades\File;

class DataTrainSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Training Data
        $trainingCsv = base_path("datasheet/dataset_training_klasifikasi_tambak.csv");
        if (File::exists($trainingCsv)) {
            $handle = fopen($trainingCsv, "r");
            fgetcsv($handle, 2000, ","); // Skip header

            $rows = [];
            while (($data = fgetcsv($handle, 2000, ",")) !== false) {
                if (count($data) >= 6) {
                    $rows[] = [
                        'suhu' => trim($data[2]),
                        'ph' => trim($data[3]),
                        'kesehatan' => trim($data[4]), // Padat Tebar
                        'ket' => trim($data[5]), // Keterangan Hasil
                    ];
                }

                if (count($rows) >= 200) {
                    DataTrain::insert($rows);
                    $rows = [];
                }
            }
            if (!empty($rows)) {
                DataTrain::insert($rows);
            }
            fclose($handle);
        }

        // 2. Seed Testing Data to hasil_naive and ikan
        $testingCsv = base_path("datasheet/dataset_testing_klasifikasi_tambak.csv");
        if (File::exists($testingCsv)) {
            $handle = fopen($testingCsv, "r");
            fgetcsv($handle, 2000, ","); // Skip header

            $tambak = Tambak::first();
            $idTambak = $tambak ? $tambak->id : 1;
            $counter = 0;

            while (($data = fgetcsv($handle, 2000, ",")) !== false) {
                if (count($data) >= 7) {
                    HasilNaive::create([
                        'keterangan' => trim($data[6]),
                        'ph' => trim($data[4]),
                        'suhu' => trim($data[3]),
                        'kesehatan' => trim($data[5]),
                        'hasil_normal' => '0.00',
                        'hasil_tidak' => '0.00',
                    ]);

                    Ikan::create([
                        'id_tambak' => $idTambak,
                        'waktu' => now()->subMinutes($counter * 30),
                        'ph' => (float) trim($data[4]),
                        'suhu' => (float) trim($data[3]),
                        'jenis_ikan' => trim($data[2]),
                    ]);

                    $counter++;
                }
            }
            fclose($handle);
        }

        // 3. Seed Prediksi Panen Dataset to prediksi table
        $prediksiCsv = base_path("datasheet/dataset_prediksi_panen_regresi_linier.csv");
        if (File::exists($prediksiCsv)) {
            $handle = fopen($prediksiCsv, "r");
            fgetcsv($handle, 2000, ","); // Skip header

            $tambak = Tambak::first();
            $idTambak = $tambak ? $tambak->id : 1;

            while (($data = fgetcsv($handle, 2000, ",")) !== false) {
                if (count($data) >= 9) {
                    $prediksiKg = trim($data[8]);
                    $bulan = trim($data[6]);
                    $jenis = trim($data[2]);

                    Prediksi::create([
                        'id_tambak' => $idTambak,
                        'prediksi' => "Bulan ke-{$bulan} ({$jenis}): {$prediksiKg} Kg",
                    ]);
                }
            }
            fclose($handle);
        }
    }
}
