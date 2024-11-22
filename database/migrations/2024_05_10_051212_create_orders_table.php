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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('buyer_id');
            $table->string('seller_id');
            $table->string('payment_id');
            $table->integer('quantity');
            $table->tinyInteger('status')->default(1)->comment('0=cancelled,1=new,2=acccepted,3=outfordelevery,4=delevered,5=completed,6=return,7=Rejected');
            $table->tinyInteger('payment_method')->comment('1=COD,2=ONLINE')->default(2);
            $table->float('price',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
