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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_id')->index();
            $table->string('parent_id')->index();
            $table->string('route_name');
            $table->boolean('is_assigned')->default(false);
            $table->string('transporter_id')->index()->nullable();
            $table->boolean('is_driver')->default(false);
            $table->string('driver_id')->index()->nullable();
            $table->boolean('trash')->default(false);
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
