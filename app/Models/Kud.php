<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kud extends Model
{
    protected $table = 'kud';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'jenis_ikan',
        'harga',
    ];

    public function tambak()
    {
        return $this->hasMany(Tambak::class, 'jenis_ikan', 'jenis_ikan');
    }
}
