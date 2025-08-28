<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'harga'];

    public function dokters()
    {
       return $this->belongsToMany(Dokter::class, 'dokter_layanan')
            ->withPivot('jadwal_id')
            ->withTimestamps();
    }
}
