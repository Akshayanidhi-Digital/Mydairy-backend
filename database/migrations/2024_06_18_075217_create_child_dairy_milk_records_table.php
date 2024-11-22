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
        Schema::create('child_dairy_milk_records', function (Blueprint $table) {
            $table->id();
            $table->string('seller_id')->index();
            $table->string('buyer_id')->index();
            $table->tinyInteger('milk_type')->default(2)->comment('0=Cow,1=Buffalow,2=Mix,3=Other');
            $table->float('quantity',8,2);
            $table->enum('shift',['M','E','D'])->default('D');
            $table->float('fat',8,2)->default(0);
            $table->float('snf',8,2)->default(0);
            $table->float('clr',8,2)->default(0);
            $table->float('bonus',8,2)->default(0);
            $table->float('price',8,2)->default(0);
            $table->float('total_price',8,2)->default(0);
            $table->date('date');
            $table->boolean('is_accepted')->default(false);
            $table->boolean('is_transport')->default(false);
            $table->string('record_id')->index()->nullable();
            $table->boolean('is_pickedup')->default(false);
            $table->boolean('is_delivered')->default(false);
            $table->tinyInteger('trash')->default(0)->comment('0=false,1=true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_dairy_milk_records');
    }
};
