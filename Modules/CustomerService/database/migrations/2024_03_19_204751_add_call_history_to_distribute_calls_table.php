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
        Schema::table('distribute_calls', function (Blueprint $table) {
            $table->unsignedBigInteger('history_call_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribute_calls', function (Blueprint $table) {
            $table->dropColumn('history_call_id');
        });
    }
};
