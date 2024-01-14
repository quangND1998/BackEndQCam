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
            // Available value: A - App, CS - Customer Service, V - Vườn
            $table->string('booking_type')->after('description')->default('A');
            // ID of creator, null mean customer create from App
            $table->unsignedBigInteger('user_id')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_visits', function (Blueprint $table) {
            $table->dropColumn('booking_type');
        });
    }
};
