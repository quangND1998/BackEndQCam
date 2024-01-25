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
        Schema::create('distribute_calls', function (Blueprint $table) {
            $table->id();
            $table->date('date_call')->nullable();
            $table->string('state')->default('pending');//chua goi dien

            $table->unsignedBigInteger('order_package_id')->nullable();
            $table->unsignedBigInteger('cskh_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribute_calls');
    }
};
