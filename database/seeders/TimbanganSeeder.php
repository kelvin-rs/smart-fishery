<?php

namespace Database\Seeders;

use App\Models\Timbangan;
use App\Models\Tambak;
use App\Models\Kud;
use Illuminate\Database\Seeder;

class TimbanganSeeder extends Seeder
{
    public function run(): void
    {
        $tambak = Tambak::first();
        $tambakId = $tambak->id ?? 1;

        $samples = [
            ['tanggal_panen' => '2026-08-20', 'banyak_panen' => 226, 'jenis_ikan' => 'Bandeng', 'total' => 4520000],
            ['tanggal_panen' => '2026-08-24', 'banyak_panen' => 335, 'jenis_ikan' => 'Windu', 'total' => 18425000],
            ['tanggal_panen' => '2026-08-28', 'banyak_panen' => 180, 'jenis_ikan' => 'Vaname', 'total' => 8100000],
        ];

        foreach ($samples as $s) {
            Timbangan::firstOrCreate(
                ['tanggal_panen' => $s['tanggal_panen'], 'id_tambak' => $tambakId],
                $s
            );
        }
    }
}
