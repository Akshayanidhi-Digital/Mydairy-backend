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
        Schema::table('milk_buy_records', function (Blueprint $table) {
            $table->tinyInteger('record_type')->default(0)->comment('0=farmer,1=user,2=unknown');
            $table->string('name')->nullable();
            $table->string('country_code')->nullable()->default('+91');
            $table->string('mobile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('milk_buy_records', function (Blueprint $table) {
            $table->dropColumn('record_type');
            $table->dropColumn('country_code');
            $table->dropColumn('name');
            $table->dropColumn('mobile');
        });
    }
};
