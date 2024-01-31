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
        Schema::create('product_histories', function (Blueprint $table) {
            $table->id();
            $table->string('unit')->default('Hộp')->nullable();
            $table->integer('expected_quantity');
            $table->integer('actual_quantity');
            $table->timestampTz("date_add")->nullable();
            $table->timestampTz("date_expire")->nullable();
            $table->string('state')->default('pending');
            $table->string('state_confirm')->nullable();

            $table->unsignedBigInteger('product_retail_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_histories');
    }
};
