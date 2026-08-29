<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tambak extends Model
{
    protected $table = 'tambak';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'alamat',
        'banyak_benih',
        'jenis_ikan',
        'nomor',
    ];

    public function timbangan()
    {
        return $this->hasMany(Timbangan::class, 'id_tambak', 'id');
    }

    public function prediksi()
    {
        return $this->hasMany(Prediksi::class, 'id_tambak', 'id');
    }

    public function ikan()
    {
        return $this->hasMany(Ikan::class, 'id_tambak', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_tambak', 'id');
    }

    public function kud()
    {
        return $this->belongsTo(Kud::class, 'jenis_ikan', 'jenis_ikan');
    }
}
