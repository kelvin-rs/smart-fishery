<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tambak extends Model
{
    protected $table = 'tambak';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_tambak',
        'user_id',
        'alamat',
        'banyak_benih',
        'luas_lahan',
        'jenis_ikan',
        'nomor',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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

    /**
     * Kepadatan tebar (ekor per m²) = banyak_benih / luas_lahan (1 desimal)
     */
    public function getKepadatanAttribute(): float
    {
        $luas = (float) ($this->luas_lahan ?: 0);
        return $luas > 0 ? round(((float) $this->banyak_benih) / $luas, 1) : 0.0;
    }

    /**
     * Rentang normal padat tebar [min, max] sesuai komoditas (config/smartfishery.php)
     */
    public function getRentangPadatTebarAttribute(): array
    {
        $map = config('smartfishery.padat_tebar_range', []);
        $default = ['min' => 5.6, 'max' => 8.4];
        $range = $map[$this->jenis_ikan] ?? ($map['Bandeng'] ?? $default);

        return [
            'min' => (float) ($range['min'] ?? 5.6),
            'max' => (float) ($range['max'] ?? 8.4),
        ];
    }

    /**
     * Label teks rentang normal padat tebar (contoh: "5,6 – 8,4")
     */
    public function getBatasPadatTebarAttribute(): string
    {
        $r = $this->rentang_padat_tebar;
        $fmt = fn(float $v) => rtrim(rtrim(number_format($v, 1, ',', '.'), '0'), ',');
        return $fmt($r['min']) . ' – ' . $fmt($r['max']);
    }

    /**
     * Status padat tebar: "Normal" jika min <= kepadatan <= max, selain itu "Tidak Normal"
     */
    public function getStatusPadatTebarAttribute(): string
    {
        $r = $this->rentang_padat_tebar;
        $k = $this->kepadatan;
        return ($k >= $r['min'] && $k <= $r['max']) ? 'Normal' : 'Tidak Normal';
    }
}
