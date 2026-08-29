<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataTrain extends Model
{
    protected $table = 'data_train';
    protected $primaryKey = 'no_train';
    public $timestamps = false;

    protected $fillable = [
        'no_train',
        'ph',
        'suhu',
        'kesehatan',
        'ket',
    ];
}
