<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking';

    protected $fillable = [
        'mulai_perjalanan',
        'selesai_perjalanan',
        'jarak_tempuh',
        'route_history',
        'emisi_perjalanan',
        'id_pengendara',
        'id_kendaraan',
    ];

    // Optional: Relationship to access Vehicle details
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }
}
