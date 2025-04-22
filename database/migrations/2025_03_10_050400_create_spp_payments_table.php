<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::create('spp_payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('produk_id')->nullable()->constrained('produks')->onDelete('cascade');
        $table->string('bulan');
        $table->decimal('jumlah', 10, 2);
        $table->string('status');
        $table->string('bukti_pembayaran')->nullable();
        $table->timestamps();
    });    
    
}


    public function down(): void
    {
        Schema::dropIfExists('spp_payments');
    }
};
