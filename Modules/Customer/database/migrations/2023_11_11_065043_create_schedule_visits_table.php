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
        Schema::create('schedule_visits', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('date_time');
            $table->integer('number_adult');
            $table->integer('number_children');
            $table->string('state'); // đã đặt, xác nhận lịch trình, đã keesrt thúc
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('product_service_owner_id');
            $table->foreign('product_service_owner_id')->references('id')->on('product_service_owners');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_visits');
    }
};
