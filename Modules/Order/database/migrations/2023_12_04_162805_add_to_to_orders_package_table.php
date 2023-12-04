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
        Schema::table('order_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('to_id')->nullable();
            $table->string('customer_resources')->nullable();
            $table->unsignedBigInteger('customer_resources_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_packages', function (Blueprint $table) {
            $table->dropColumn('to_id');
            $table->dropColumn('customer_resources');
            $table->dropColumn('customer_resources_id');
        });
    }
};
