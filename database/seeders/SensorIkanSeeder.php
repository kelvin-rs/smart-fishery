<?php

namespace Database\Seeders;

use App\Models\Ikan;
use App\Models\Tambak;
use Illuminate\Database\Seeder;

class SensorIkanSeeder extends Seeder
{
    public function run(): void
    {
        $tambak = Tambak::first();
        $tambakId = $tambak->id ?? 1;

        // 21 data sensor aktual sesuai Tabel 4.3 Dokumen
        $samples = [
            ['ph' => 7.70, 'suhu' => 30.81, 'waktu' => '2026-08-29 15:34:26'],
            ['ph' => 8.19, 'suhu' => 31.50, 'waktu' => '2026-08-29 15:34:39'],
            ['ph' => 7.13, 'suhu' => 30.75, 'waktu' => '2026-08-29 15:34:48'],
            ['ph' => 7.68, 'suhu' => 30.75, 'waktu' => '2026-08-29 15:35:09'],
            ['ph' => 8.19, 'suhu' => 31.44, 'waktu' => '2026-08-29 15:35:22'],
            ['ph' => 7.12, 'suhu' => 30.56, 'waktu' => '2026-08-29 15:35:32'],
            ['ph' => 8.19, 'suhu' => 31.37, 'waktu' => '2026-08-29 15:36:06'],
            ['ph' => 7.12, 'suhu' => 30.50, 'waktu' => '2026-08-29 15:36:15'],
            ['ph' => 8.19, 'suhu' => 31.37, 'waktu' => '2026-08-29 15:36:50'],
            ['ph' => 7.10, 'suhu' => 30.50, 'waktu' => '2026-08-29 15:36:59'],
            ['ph' => 7.68, 'suhu' => 30.62, 'waktu' => '2026-08-29 15:37:19'],
            ['ph' => 8.21, 'suhu' => 31.37, 'waktu' => '2026-08-29 15:37:33'],
            ['ph' => 7.68, 'suhu' => 30.56, 'waktu' => '2026-08-29 15:38:02'],
            ['ph' => 8.19, 'suhu' => 31.31, 'waktu' => '2026-08-29 15:38:16'],
            ['ph' => 7.09, 'suhu' => 30.44, 'waktu' => '2026-08-29 15:38:30'],
            ['ph' => 7.68, 'suhu' => 30.56, 'waktu' => '2026-08-29 15:38:46'],
            ['ph' => 8.19, 'suhu' => 31.31, 'waktu' => '2026-08-29 15:39:00'],
            ['ph' => 7.10, 'suhu' => 30.44, 'waktu' => '2026-08-29 15:39:09'],
            ['ph' => 7.68, 'suhu' => 30.50, 'waktu' => '2026-08-29 15:39:29'],
            ['ph' => 8.19, 'suhu' => 31.31, 'waktu' => '2026-08-29 15:39:43'],
            ['ph' => 7.09, 'suhu' => 30.31, 'waktu' => '2026-08-29 15:39:52'],
        ];

        foreach ($samples as $s) {
            Ikan::firstOrCreate(
                ['waktu' => $s['waktu'], 'id_tambak' => $tambakId],
                [
                    'ph' => $s['ph'],
                    'suhu' => $s['suhu'],
                    'jenis_ikan' => 'Bandeng'
                ]
            );
        }
    }
}
