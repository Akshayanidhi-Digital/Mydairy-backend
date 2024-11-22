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
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('farmer_id');
            $table->string('name');
            $table->string('father_name');
            $table->string('country_code')->default('+91');
            $table->string('mobile')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('parent_id');
            $table->tinyInteger('is_fixed_rate')->default(0)->comment('0=false,1=true');
            $table->tinyInteger('fixed_rate_type')->default(0)->comment('0=rate,1=fat_rate');
            $table->float('rate',8,2)->default(0);
            $table->float('fat_rate',8,2)->default(0);
            $table->tinyInteger('trash')->default(0)->comment('0=false,1=true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
