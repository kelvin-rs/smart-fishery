<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kud extends Model
{
    protected $table = 'kud';

    public function tambak()
    {
        return $this->hasMany(Tambak::class, 'jenis_ikan', 'jenis_ikan');
    }

    public function ikan()
    {
        return $this->hasMany(Ikan::class, 'jenis_ikan', 'jenis_ikan');
    }
}
