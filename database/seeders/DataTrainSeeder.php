<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataTrain;

class DataTrainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("datasheet/dataset_training_klasifikasi_tambak.csv"), "r");
        
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $dataTrain = new DataTrain();
                // Indeks 2: suhu_c, 3: ph, 4: padat_tebar, 5: hasil_klasifikasi
                $dataTrain->ph = $data[3] ?? null;
                $dataTrain->suhu = $data[2] ?? null;
                
                // Asumsi: kolom 'padat_tebar' atau informasi lain akan dimasukkan ke 'kesehatan'
                $dataTrain->kesehatan = $data[4] ?? null; 
                
                $dataTrain->ket = $data[5] ?? null;
                $dataTrain->save();
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
