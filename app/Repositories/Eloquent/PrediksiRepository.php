<?php

namespace App\Repositories\Eloquent;

use App\Models\Prediksi;
use App\Repositories\Contracts\PrediksiRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PrediksiRepository implements PrediksiRepositoryInterface
{
    public function getAll(): Collection
    {
        return Prediksi::with('tambak')->get();
    }

    public function getByTambak(string $idTambak): Collection
    {
        return Prediksi::where('id_tambak', $idTambak)->orderBy('id_hasil', 'desc')->get();
    }

    public function getLatest(int $limit = 10): Collection
    {
        return Prediksi::with('tambak')->orderBy('id_hasil', 'desc')->take($limit)->get();
    }

    public function create(array $data): Prediksi
    {
        return Prediksi::create($data);
    }
}
