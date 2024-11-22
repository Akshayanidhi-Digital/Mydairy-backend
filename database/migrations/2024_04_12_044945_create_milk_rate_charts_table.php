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
        Schema::create('milk_rate_charts', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->enum('chart_type',['Sell','Purchase']);
            $table->enum('milk_type',['Cow','Buffalo','Mix','Other']);
            $table->float('fat',8,2);
            $table->float('snf',8,2);
            $table->float('rate',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_rate_charts');
    }
};
