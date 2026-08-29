<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timbangan extends Model
{
    protected $table = 'timbangan';

    public function tambak()
    {
        return $this->belongsTo(Tambak::class, 'id_tambak', 'id_tambak');
    }
}
