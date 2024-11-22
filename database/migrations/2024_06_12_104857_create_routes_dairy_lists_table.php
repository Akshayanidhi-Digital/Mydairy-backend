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
        Schema::create('routes_dairy_lists', function (Blueprint $table) {
            $table->id();
            $table->string('dairy_id')->index();
            $table->string('route_id')->index();
            $table->string('parent_id')->index();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('routes_dairy_lists');
    }
};
