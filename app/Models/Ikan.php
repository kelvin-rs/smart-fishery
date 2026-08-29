<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ikan extends Model
{
    protected $table = 'ikan';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_tambak',
        'waktu',
        'ph',
        'suhu',
        'jenis_ikan',
    ];

    public function tambak()
    {
        return $this->belongsTo(Tambak::class, 'id_tambak', 'id');
    }
}
