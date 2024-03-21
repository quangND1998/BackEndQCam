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
        Schema::table('history_calls', function (Blueprint $table) {
            $table->string('note');
            $table->unsignedBigInteger('distribute_call_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_calls', function (Blueprint $table) {
            $table->dropColumn('note');
            $table->dropColumn('distribute_call_id');
        });
    }
};