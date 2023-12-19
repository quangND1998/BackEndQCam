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
        Schema::create('commissions_packages', function (Blueprint $table) {
            $table->id();
            // $table->decimal('grand_total', 20)->nullable();
            // $table->decimal('value_sale', 20)->nullable();
            // $table->decimal('value_sm', 20)->nullable();
            // $table->decimal('value_telesale', 20)->nullable();
            // $table->decimal('value_ctv', 20)->nullable();
            $table->string('status')->nullable();

            $table->unsignedBigInteger('order_package_id')->nullable();
            $table->foreign('order_package_id')->references('id')->on('order_packages')->onDelete('cascade');

            // chính sach hoa hong
            $table->unsignedBigInteger('commissions_id')->nullable();
            $table->foreign('commissions_id')->references('id')->on('commissions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions_packages');
    }
};
