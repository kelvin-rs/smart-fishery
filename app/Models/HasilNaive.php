<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilNaive extends Model
{
    protected $table = 'hasil_naive';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'id_tambak',
        'keterangan',
        'ph',
        'suhu',
        'kesehatan',
        'hasil_tidak',
        'hasil_normal',
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
