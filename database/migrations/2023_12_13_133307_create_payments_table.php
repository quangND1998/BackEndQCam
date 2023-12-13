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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('OrderNo')->nullable();
            $table->decimal('OrderCash', 30, 2)->nullable();
            $table->integer('PaymentStatus')->nullable();
            $table->integer('PaymentMethod')->nullable();
            $table->string('PurchaseDate')->nullable();
            $table->string('MerchantUsername')->nullable();
            $table->string('ShopId')->nullable();
            $table->string('BankName')->nullable();
            $table->string('CardNumber')->nullable();
            $table->string('BillingCode')->nullable();
            $table->integer('CardIssuanceType')->nullable();
            $table->string('Customer_identifier')->nullable();
            $table->string('MDD1')->nullable();
            $table->string('MDD2')->nullable();
            $table->string('Token')->nullable();
            $table->decimal('VoucherTotalAmount', 30, 2)->nullable();
            $table->string('VoucherDescription')->nullable();
            $table->boolean('IsQRStatic')->default(true)->nullable();
            $table->unsignedBigInteger('order_id')->index();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
