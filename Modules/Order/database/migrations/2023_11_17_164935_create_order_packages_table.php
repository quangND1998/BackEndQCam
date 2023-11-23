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
        Schema::create('order_packages', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('status')->nullable();

            $table->unsignedInteger('item_count')->nullable();

            $table->boolean('payment_status')->default(0);
            $table->string('payment_method')->nullable();
            // gia tam thoi
            $table->decimal('grand_total', 20)->nullable();
            $table->integer('price_percent')->default(0)->nullable();

            // triết khấu
            $table->integer('discount')->default(0)->nullable();
            //phí ship
            $table->integer('shipping_fee')->nullable();
            //giá phải trả
            $table->integer('last_price')->nullable();
            $table->string('name')->nullable();
            $table->text('specific_address')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('wards')->nullable();
            $table->text('notes')->nullable();
            $table->text('reason')->nullable();
            $table->integer('vat')->nullable();
            $table->integer("discount_deal")->nullable();
            $table->integer("type")->nullable();

            $table->integer("product_selected")->nullable();
            $table->timestampTz("time_approve")->nullable();
            $table->timestampTz("time_end")->nullable();
            $table->integer("time_reservations")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_packages');
    }
};
