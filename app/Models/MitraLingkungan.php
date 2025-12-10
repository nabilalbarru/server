<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MitraLingkungan extends Model
{
    use HasFactory;

    protected $table = 'mitra_lingkungan';

    protected $fillable = [
        'nama_mitra',
        'deskripsi',
        'fokus_area',
        'url'
    ];

    public function rekening()
    {
        // return $this->hasMany(RekeningMitra::class, 'mitra_id');
    }
}
