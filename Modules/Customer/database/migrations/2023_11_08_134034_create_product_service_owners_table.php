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
        Schema::create('product_service_owners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('product_service_id')->unique()->nullable();
            $table->foreign('product_service_id')->references('id')->on('product_services')->onDelete('cascade');
            $table->timestampTz("time_approve")->nullable();
            $table->longText("description")->nullable();
            $table->integer("number_deliveries_current")->nullable();
            $table->string("state")->nullable();
            $table->integer("visited_time")->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_service_owners');
    }
};
