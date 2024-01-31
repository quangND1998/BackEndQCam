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
        Schema::create('refund_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('state')->nullable();
            $table->string('code')->nullable();
            $table->dateTime('time')->nullable();
            $table->string('type')->nullable();
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->string('order_transport_number')->nullable();
            $table->string('order_number')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_products');
    }
};
