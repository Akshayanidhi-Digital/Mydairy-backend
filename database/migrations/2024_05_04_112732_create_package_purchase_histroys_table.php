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
        Schema::create('package_purchase_histroys', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id');
            $table->string('user_id');
            $table->string('payment_id')->nullable();
            $table->string('tnx_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->tinyInteger('payment_status')->default(1)->comment('1=initiated,2=complete,3=cancelled');
            $table->float('amount')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=active,0=inactive,2=expired');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_purchase_histroys');
    }
};
