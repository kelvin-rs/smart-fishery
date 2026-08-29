<?php

namespace App\Repositories\Contracts;

use App\Models\Tambak;
use Illuminate\Database\Eloquent\Collection;

interface TambakRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id): ?Tambak;
    public function findByIdTambak(string $idTambak): ?Tambak;
    public function create(array $data): Tambak;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
