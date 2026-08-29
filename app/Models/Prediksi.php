<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediksi extends Model
{
    protected $table = 'prediksi';
    protected $primaryKey = 'id_hasil';

    public function tambak()
    {
        return $this->belongsTo(Tambak::class, 'id_tambak', 'id_tambak');
    }
}
