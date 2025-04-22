<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppPayment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'produk_id', 'bulan', 'jumlah', 'status', 'bukti_pembayaran'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);  // Menambahkan relasi dengan model Produk
    }
    public function getBuktiPembayaranUrlAttribute()
{
    return $this->bukti_pembayaran ? asset('storage/' . $this->bukti_pembayaran) : null;
}

}
