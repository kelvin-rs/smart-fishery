<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tambak extends Model
{
    protected $table = 'tambak';

    public function timbangan()
    {
        return $this->hasMany(Timbangan::class, 'id_tambak', 'id_tambak');
    }

    public function prediksi()
    {
        return $this->hasMany(Prediksi::class, 'id_tambak', 'id_tambak');
    }

    public function ikan()
    {
        return $this->hasMany(Ikan::class, 'id_tambak', 'id_tambak');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_tambak', 'id_tambak');
    }

    public function kud()
    {
        return $this->belongsTo(Kud::class, 'jenis_ikan', 'jenis_ikan');
    }
}
