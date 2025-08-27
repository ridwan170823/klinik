<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'antrian_id',
        'amount',
        'payment_status',
        'payment_method',
        'paid_at',
    ];

    public function antrian()
    {
        return $this->belongsTo(Antrian::class);
    }
}