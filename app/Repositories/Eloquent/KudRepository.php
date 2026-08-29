<?php

namespace App\Repositories\Eloquent;

use App\Models\Kud;
use App\Repositories\Contracts\KudRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class KudRepository implements KudRepositoryInterface
{
    public function getAll(): Collection
    {
        return Kud::all();
    }

    public function findByJenisIkan(string $jenisIkan): ?Kud
    {
        return Kud::where('jenis_ikan', $jenisIkan)->first();
    }

    public function updateOrCreatePrice(string $jenisIkan, int $harga): Kud
    {
        return Kud::updateOrCreate(
            ['jenis_ikan' => $jenisIkan],
            ['harga' => $harga]
        );
    }
}
