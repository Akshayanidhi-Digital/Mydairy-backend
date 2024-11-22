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
        Schema::create('pakeages', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id');
            $table->string('name');
            $table->enum('category',['single', 'multiple']);
            $table->string('user_count')->default(0);
            $table->float('price',8,2)->default(0);
            $table->integer('duration')->default(0);
            $table->enum('duration_type',['day', 'month', 'year'])->default('month');
            $table->string('description')->nullable();
            $table->enum('status',['active', 'inactive'])->default('active');
            $table->string('farmer_count')->default(0);
            $table->json('module_access')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakeages');
    }
};
