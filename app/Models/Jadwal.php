<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
     protected $casts = [
        'is_available' => 'boolean',
    ];

    public function dokters()
    {
        return $this->belongsToMany(Dokter::class, 'dokter_jadwal');
    }
}
