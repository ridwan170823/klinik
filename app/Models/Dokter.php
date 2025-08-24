<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'nama',
        'spesialis',
        // HAPUS 'jadwal_id' di sini
    ];

    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'dokter_jadwal')->withTimestamps();
    }
}
