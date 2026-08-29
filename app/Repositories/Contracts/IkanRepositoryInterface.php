<?php

namespace App\Repositories\Contracts;

use App\Models\Ikan;
use Illuminate\Database\Eloquent\Collection;

interface IkanRepositoryInterface
{
    public function getAll(): Collection;
    public function getLatestByTambak(string $idTambak, int $limit = 15): Collection;
    public function create(array $data): Ikan;
}
