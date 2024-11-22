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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->index();
            $table->string('product_id')->index();
            $table->string('name');
            $table->string('image');
            $table->string('unit_type');
            $table->float('price',8,2);
            $table->integer('weight');
            $table->float('tax',8,2)->default(0);
            $table->float('discount',8,2)->default(0);
            $table->integer('quantity');
            $table->float('total',8,2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
