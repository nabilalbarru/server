<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';

    protected $fillable = [
        'jenis_kendaraan',
        'kategori_emisi',
        'emisi_perjalanan',
        'deskripsi_cc_kendaraan',
        'icon'
    ];
}
