<?php

namespace App\Repositories\Eloquent;

use App\Models\Tambak;
use App\Repositories\Contracts\TambakRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TambakRepository implements TambakRepositoryInterface
{
    public function getAll(): Collection
    {
        return Tambak::all();
    }

    public function findById(int $id): ?Tambak
    {
        return Tambak::find($id);
    }

    public function findByIdTambak(string $idTambak): ?Tambak
    {
        return Tambak::where('id_tambak', $idTambak)->first();
    }

    public function create(array $data): Tambak
    {
        return Tambak::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $tambak = $this->findById($id);
        return $tambak ? $tambak->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $tambak = $this->findById($id);
        return $tambak ? $tambak->delete() : false;
    }
}
