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
        Schema::table('reminds', function (Blueprint $table) {
            $table->string('state')->nullable();
            $table->unsignedBigInteger('call_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminds', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->dropColumn('call_id');
        });
    }
};
