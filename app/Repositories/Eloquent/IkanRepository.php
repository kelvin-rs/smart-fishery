<?php

namespace App\Repositories\Eloquent;

use App\Models\Ikan;
use App\Repositories\Contracts\IkanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class IkanRepository implements IkanRepositoryInterface
{
    public function getAll(): Collection
    {
        return Ikan::all();
    }

    public function getLatestByTambak(string $idTambak, int $limit = 15): Collection
    {
        return Ikan::where('id_tambak', $idTambak)
            ->orderBy('id', 'desc')
            ->take($limit)
            ->get();
    }

    public function create(array $data): Ikan
    {
        return Ikan::create($data);
    }
}
