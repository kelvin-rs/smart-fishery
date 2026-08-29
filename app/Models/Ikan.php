<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ikan extends Model
{
    protected $table = 'ikan';

    public function tambak()
    {
        return $this->belongsTo(Tambak::class, 'id_tambak', 'id_tambak');
    }

    public function kud()
    {
        return $this->belongsTo(Kud::class, 'jenis_ikan', 'jenis_ikan');
    }
}
