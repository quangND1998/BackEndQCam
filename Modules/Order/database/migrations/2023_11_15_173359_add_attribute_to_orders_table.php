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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('vat')->nullable();
            $table->integer("discount_deal")->nullable();
            $table->string("type")->nullable();
            // $table->string("amount_paid")->nullable();
            // $table->unsignedBigInteger('product_service_owner_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
        });
    }
};
