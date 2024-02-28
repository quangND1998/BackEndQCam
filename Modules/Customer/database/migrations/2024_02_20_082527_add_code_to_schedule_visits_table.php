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
        Schema::table('schedule_visits', function (Blueprint $table) {
            $table->string('code')->nullable();
            $table->string('log')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_visits', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('log');
        });
    }
};
