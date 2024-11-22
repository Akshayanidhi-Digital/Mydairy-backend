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
        Schema::table('users', function (Blueprint $table) {
            $table->string('fcm_token')->nullable();
        });
        Schema::table('farmers', function (Blueprint $table) {
            $table->string('fcm_token')->nullable();
        });
        Schema::table('buyers', function (Blueprint $table) {
            $table->string('fcm_token')->nullable();
        });
        Schema::table('transporters', function (Blueprint $table) {
            $table->string('fcm_token')->nullable();
        });
        Schema::table('transport_drivers', function (Blueprint $table) {
            $table->string('fcm_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
        Schema::table('buyers', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
        Schema::table('transporters', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
        Schema::table('transport_drivers', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
    }
};
