<?php

namespace App\Repositories\Eloquent;

use App\Models\DataTrain;
use App\Repositories\Contracts\DataTrainRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DataTrainRepository implements DataTrainRepositoryInterface
{
    public function getAll(): Collection
    {
        return DataTrain::all();
    }

    public function getByWaktu(string $waktu): Collection
    {
        return DataTrain::where('waktu', $waktu)->get();
    }

    public function count(): int
    {
        return DataTrain::count();
    }

    public function insertBatch(array $rows): bool
    {
        return DataTrain::insert($rows);
    }

    public function create(array $data): DataTrain
    {
        return DataTrain::create($data);
    }
}
