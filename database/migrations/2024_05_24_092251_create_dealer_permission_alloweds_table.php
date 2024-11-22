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
        Schema::create('dealer_permission_alloweds', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('role_id');
            $table->string('permission_id');
            $table->boolean('access')->default(false);
            $table->timestamps();

            $table->foreign('role_id')->references('role_id')->on('dealer_roles');
            $table->foreign('permission_id')->references('permission_id')->on('dealer_role_permissions');

            $table->index('user_id');
            $table->index('role_id');
            $table->index('permission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_permission_alloweds');
    }
};
