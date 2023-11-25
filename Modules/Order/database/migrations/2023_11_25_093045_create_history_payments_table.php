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
        Schema::create('history_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_package_id')->index();
            $table->foreign('order_package_id')->references('id')->on('order_packages')->onDelete('cascade');
            $table->string('payment_method')->nullable();
            $table->decimal('amount_received', 20)->nullable();
            $table->timestampTz("payment_date")->nullable();
            $table->string('status')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_payments');
    }
};
