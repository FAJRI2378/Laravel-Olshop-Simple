<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Menambahkan relasi ke SppPayment
    public function sppPayments()
    {
        return $this->hasMany(SppPayment::class);
    }
}
