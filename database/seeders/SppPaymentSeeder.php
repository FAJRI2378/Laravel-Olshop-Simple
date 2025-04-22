<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SppPayment;
use App\Models\User;

class SppPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Ambil user pertama

        if ($user) {
            $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            foreach ($months as $month) {
                SppPayment::create([
                    'user_id' => $user->id,
                    'bulan' => $month,
                    'jumlah' => 500000, // Contoh jumlah SPP
                    'detail' => 'SPP Bulanan'
                ]);
            }
        }
    }
}
