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
        Schema::create('userplanpackages', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->string('plan_id');
            $table->string('massage_plan');
            $table->string('massage_plan_created');
            $table->string('massage_plan_expire_date');
            $table->string('massage_plan_limit');
            $table->string('user_count');
            $table->string('category_plan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userplanpackages');
    }
};
