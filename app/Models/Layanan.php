<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dokters()
    {
       return $this->belongsToMany(Dokter::class, 'dokter_layanan')
            ->withPivot('jadwal_id')
            ->withTimestamps();
    }
}
