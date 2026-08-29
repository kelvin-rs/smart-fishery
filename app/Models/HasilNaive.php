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
        'keterangan',
        'ph',
        'suhu',
        'kesehatan',
        'hasil_tidak',
        'hasil_normal',
    ];
}
