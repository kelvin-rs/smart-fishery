<?php

namespace App\Repositories\Contracts;

use App\Models\Kud;
use Illuminate\Database\Eloquent\Collection;

interface KudRepositoryInterface
{
    public function getAll(): Collection;
    public function findByJenisIkan(string $jenisIkan): ?Kud;
    public function updateOrCreatePrice(string $jenisIkan, int $harga): Kud;
}
