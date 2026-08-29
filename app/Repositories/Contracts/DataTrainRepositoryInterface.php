<?php

namespace App\Repositories\Contracts;

use App\Models\DataTrain;
use Illuminate\Database\Eloquent\Collection;

interface DataTrainRepositoryInterface
{
    public function getAll(): Collection;
    public function getByWaktu(string $waktu): Collection;
    public function count(): int;
    public function insertBatch(array $rows): bool;
    public function create(array $data): DataTrain;
}
