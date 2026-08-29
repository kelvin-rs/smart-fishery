<?php

namespace App\Repositories\Contracts;

use App\Models\Prediksi;
use Illuminate\Database\Eloquent\Collection;

interface PrediksiRepositoryInterface
{
    public function getAll(): Collection;
    public function getByTambak(string $idTambak): Collection;
    public function getLatest(int $limit = 10): Collection;
    public function create(array $data): Prediksi;
}
