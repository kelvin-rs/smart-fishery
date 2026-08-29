<?php

namespace App\Repositories\Contracts;

use App\Models\HasilNaive;
use Illuminate\Database\Eloquent\Collection;

interface HasilNaiveRepositoryInterface
{
    public function getAll(): Collection;
    public function getLatest(int $limit = 10): Collection;
    public function create(array $data): HasilNaive;
}
