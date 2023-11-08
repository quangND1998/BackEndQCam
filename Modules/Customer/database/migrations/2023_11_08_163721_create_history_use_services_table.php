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
        Schema::create('history_use_services', function (Blueprint $table) {
            $table->id();
            $table->timestampTz("date")->nullable();
            $table->longText("drescription")->nullable();
            $table->string("state")->nullable();
            $table->unsignedBigInteger('product_service_owner_id')->unique()->nullable();
            $table->foreign('product_service_owner_id')->references('id')->on('product_service_owners')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_use_services');
    }
};
