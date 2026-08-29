<?php

namespace App\Repositories\Contracts;

use App\Models\Timbangan;
use Illuminate\Database\Eloquent\Collection;

interface TimbanganRepositoryInterface
{
    public function getAll(): Collection;
    public function getByTambak(string $idTambak): Collection;
    public function create(array $data): Timbangan;
}
