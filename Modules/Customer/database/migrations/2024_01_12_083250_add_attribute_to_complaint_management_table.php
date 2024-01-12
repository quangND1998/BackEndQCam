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
        Schema::table('complaint_management', function (Blueprint $table) {
            $table->json('data')->nullable();
            $table->float('star')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint_management', function (Blueprint $table) {
            $table->json('data')->nullable();
            $table->json('star')->nullable();
        });
    }
};
