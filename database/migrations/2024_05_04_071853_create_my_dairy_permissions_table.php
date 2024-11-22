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
        Schema::create('my_dairy_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // Permission name
            $table->string('guard_name'); // Optional guard name
            $table->string('user_id'); // Foreign key for User model
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_dairy_permissions');
    }
};
