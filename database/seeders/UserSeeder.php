<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tambak;
use App\Models\Kud;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Contoh Tambak
        $tambak = Tambak::firstOrCreate(
            ['alamat' => 'Sidoarjo, Jawa Timur'],
            [
                'banyak_benih' => 5000,
                'jenis_ikan' => 'Bandeng',
                'nomor' => 1
            ]
        );

        // 2. Buat Data Contoh Harga KUD
        Kud::firstOrCreate(['jenis_ikan' => 'Bandeng'], ['harga' => 20000]);
        Kud::firstOrCreate(['jenis_ikan' => 'Vaname'], ['harga' => 45000]);
        Kud::firstOrCreate(['jenis_ikan' => 'Windu'], ['harga' => 55000]);

        // 3. Buat Akun Petambak
        User::updateOrCreate(
            ['email' => 'petambak@smartfishery.id'],
            [
                'username' => 'Siska Amalia',
                'id_tambak' => $tambak->id,
                'password' => Hash::make('password123'),
                'role' => 'petambak'
            ]
        );

        // 4. Buat Akun KUD
        User::updateOrCreate(
            ['email' => 'kud@smartfishery.id'],
            [
                'username' => 'Pengurus KUD Nelayan',
                'id_tambak' => null,
                'password' => Hash::make('password123'),
                'role' => 'kud'
            ]
        );
    }
}
