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
        Schema::create('tree_owner', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tree_id')->unique()->nullable();
            $table->foreign('tree_id')->references('id')->on('trees')->onDelete('cascade');
            $table->unsignedBigInteger('product_service_id')->unique()->nullable();
            $table->foreign('product_service_id')->references('id')->on('product_services')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_owner');
    }
};
