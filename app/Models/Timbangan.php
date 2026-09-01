<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timbangan extends Model
{
    protected $table = 'timbangan';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_tambak',
        'user_id',
        'tanggal_panen',
        'banyak_panen',
        'jenis_ikan',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tambak()
    {
        return $this->belongsTo(Tambak::class, 'id_tambak', 'id');
    }
}
