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
        Schema::create('booking_histories', function (Blueprint $table) {
            $table->id();

            $table->integer("ballot_code");
            $table->timestamps();

            $table->timestampTz("dateStart")->nullable();
            $table->timestampTz("dateEnd")->nullable();
            $table->string('status')->nullable();
            $table->longText('reason');
            
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->foreign('ref_id')->references('id')->on('users');

            $table->unsignedBigInteger('bookings_id')->nullable();
            $table->foreign('bookings_id')->references('id')->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_histories');
    }
};
