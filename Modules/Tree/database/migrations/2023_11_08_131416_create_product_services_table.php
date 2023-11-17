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
        Schema::create('product_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_priority')->nullable();
            $table->string('name')->nullable();
            $table->integer('number_tree')->nullable();
            $table->float('acreage')->nullable();
            $table->integer('free_visit')->nullable();
            $table->integer('amount_products_received')->nullable();
            $table->bigInteger('price')->nullable();
            $table->integer('number_deliveries')->nullable();

            $table->integer('life_time')->nullable();
            $table->longText('description')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('status')->default(true)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_services');
    }
};
