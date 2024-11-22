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
        Schema::create('milk_transport_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_id')->index();
            $table->string('seller_id')->index();
            $table->string('buyer_id')->index();
            $table->string('route_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_transport_records');
    }
};
