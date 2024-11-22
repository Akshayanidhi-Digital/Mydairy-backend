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
        Schema::create('transport_drivers', function (Blueprint $table) {
            $table->id();
            $table->string('transporter_id')->index();
            $table->string('driver_id')->index();
            $table->string('name');
            $table->string('father_name');
            $table->string('country_code')->default('+91');
            $table->string('mobile')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->boolean('is_verified')->default(false)->comment('0=false,1=true');
            $table->boolean('is_blocked')->default(false)->comment('0=false,1=true');
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_drivers');
    }
};
