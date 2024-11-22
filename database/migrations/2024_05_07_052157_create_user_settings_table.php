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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->enum('lang',['hi','en'])->default('en');
            $table->enum('print_font_size',['N','M','L'])->default('N');
            $table->enum('wight',['W','Q','L'])->default('W');
            $table->enum('print_size',['2','3'])->default('2');
            $table->boolean('print_recipt')->default(true);
            $table->boolean('print_recipt_all')->default(false);
            $table->boolean('whatsapp_message')->default(false);
            $table->boolean('auto_fats')->default(false);
            $table->boolean('rate_par_kg')->default(false);
            $table->boolean('fat_rate')->default(false);
            $table->boolean('snf')->default(false);
            $table->boolean('bonus')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
