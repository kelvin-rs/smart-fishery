<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediksi extends Model
{
    protected $table = 'prediksi';
    protected $primaryKey = 'id_hasil';
    public $timestamps = false;

    protected $fillable = [
        'id_hasil',
        'user_id',
        'id_tambak',
        'jenis_ikan',
        'bulan',
        'keadaan_tambak',
        'prediksi',
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
