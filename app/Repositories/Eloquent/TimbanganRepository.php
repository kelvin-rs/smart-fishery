<?php

namespace App\Repositories\Eloquent;

use App\Models\Timbangan;
use App\Repositories\Contracts\TimbanganRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TimbanganRepository implements TimbanganRepositoryInterface
{
    public function getAll(): Collection
    {
        return Timbangan::with('tambak')->get();
    }

    public function getByTambak(string $idTambak): Collection
    {
        return Timbangan::where('id_tambak', $idTambak)->get();
    }

    public function create(array $data): Timbangan
    {
        return Timbangan::create($data);
    }
}
