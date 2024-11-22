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
        Schema::create('messages_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->index();
            $table->string('message');
            $table->boolean('is_marked')->default(false);
            $table->tinyInteger('message_type')->default('1')->comment('1=message,2=milk request');
            $table->bigInteger('record_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages_alerts');
    }
};
