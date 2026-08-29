<?php

namespace App\Repositories\Eloquent;

use App\Models\HasilNaive;
use App\Repositories\Contracts\HasilNaiveRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class HasilNaiveRepository implements HasilNaiveRepositoryInterface
{
    public function getAll(): Collection
    {
        return HasilNaive::all();
    }

    public function getLatest(int $limit = 10): Collection
    {
        return HasilNaive::orderBy('id_hasil', 'desc')->take($limit)->get();
    }

    public function create(array $data): HasilNaive
    {
        return HasilNaive::create($data);
    }
}
