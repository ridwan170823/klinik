<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_antrian',
        'dokter_id',
        'jadwal_id',
        'status',
        'layanan_id',
        'tanggal',
        'nomor',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}