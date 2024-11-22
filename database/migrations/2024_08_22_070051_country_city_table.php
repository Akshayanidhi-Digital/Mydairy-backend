<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iso2');
            $table->string('region');
            $table->string('phone_code')->nullable();
            $table->string('currency');
            $table->string('currency_name');
            $table->string('currency_symbol');
            $table->json('timezones');
            $table->boolean('status')->default(true);
        });

        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->string('name');
            $table->string('state_code');
            $table->string('type');
            $table->boolean('status')->default(true);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('state_id');
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
    }
};
