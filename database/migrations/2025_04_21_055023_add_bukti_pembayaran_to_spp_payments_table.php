<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spp_payments', function (Blueprint $table) {
            // Cek apakah kolom sudah ada
            if (!Schema::hasColumn('spp_payments', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable();
            }
            // Cek apakah kolom 'status' sudah ada
            if (!Schema::hasColumn('spp_payments', 'status')) {
                $table->enum('status', ['pending', 'waiting', 'paid'])->default('pending');
            }
        });
    }
    
public function down(): void
{
    Schema::table('spp_payments', function (Blueprint $table) {
        $table->dropColumn(['bukti_pembayaran', 'status']);
    });
}

};
