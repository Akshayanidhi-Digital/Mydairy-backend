<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('transporters', function (Blueprint $table) {
            $table->string('parent_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('transporters', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
};