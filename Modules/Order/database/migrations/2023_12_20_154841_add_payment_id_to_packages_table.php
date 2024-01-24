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
        Schema::table('commissions_packages', function (Blueprint $table) {

            // $table->unsignedBigInteger('history_payment_id')->nullable();
            // $table->foreign('history_payment_id')->references('id')->on('history_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commissions_packages', function (Blueprint $table) {
            // $table->string('history_payment_id');
        });
    }
};
