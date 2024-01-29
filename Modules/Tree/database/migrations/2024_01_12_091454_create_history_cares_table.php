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
        Schema::create('history_cares', function (Blueprint $table) {
            $table->id();
            $table->timestampTz("date")->nullable();
            $table->unsignedBigInteger('trees_id')->nullable();
            $table->foreign('trees_id')->references('id')->on('trees')->onDelete('cascade');

            $table->timestamps();
        });
        Schema::create('history_cares_activity', function (Blueprint $table) {
            $table->id();
            // history_cares
            $table->unsignedBigInteger('history_cares_id')->nullable();
            $table->foreign('history_cares_id')->references('id')->on('history_cares')->onDelete('cascade');

            $table->unsignedBigInteger('activity_cares_id')->nullable();
            $table->foreign('activity_cares_id')->references('id')->on('activity_cares')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_cares');
    }
};
