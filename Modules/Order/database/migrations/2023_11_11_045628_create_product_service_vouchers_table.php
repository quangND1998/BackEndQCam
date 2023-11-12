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
        Schema::create('product_service_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
            $table->unsignedBigInteger('voucher_id')->nullable()->index();


            $table->unsignedBigInteger('product_service_id')->nullable()->index();
            $table->foreign('product_service_id')->references('id')->on('product_services')->onDelete('cascade');

            $table->unique(['product_service_id', 'voucher_id']);

            $table->string('unit')->nullable();
            $table->decimal('price', 20, 6)->nullable();
            $table->integer('discount')->default(0)->nullable();
            $table->decimal('price_sale', 20, 6)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_service_vouchers');
    }
};
