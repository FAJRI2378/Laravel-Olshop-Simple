<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('spp_payments', function (Blueprint $table) {
            // Check if the user_id column does not exist before adding it
            if (!Schema::hasColumn('spp_payments', 'user_id')) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            }
        });
    }
    
    public function down()
    {
        Schema::table('spp_payments', function (Blueprint $table) {
            // Check if the user_id column exists before dropping the foreign key and column
            if (Schema::hasColumn('spp_payments', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
    
};
