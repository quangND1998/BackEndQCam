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
        Schema::table('shiping_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('state')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shiping_histories', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('state');
        });
    }
};
