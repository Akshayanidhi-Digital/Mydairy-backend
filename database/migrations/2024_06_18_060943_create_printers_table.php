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
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->index();
            $table->enum('printer_type',['USB','NETWORK']);
            $table->string('name');
            $table->string('port')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('trash')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
