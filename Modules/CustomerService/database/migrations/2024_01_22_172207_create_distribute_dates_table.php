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
        Schema::create('distribute_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date_recevie')->nullable();
            $table->string('state')->default('pending');//chua nhan
            $table->string('index_number');

            $table->unsignedBigInteger('order_package_id')->nullable();
            $table->unsignedBigInteger('product_service_owner_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribute_dates');
    }
};
