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
            $table->decimal('commission_percentage', 20)->nullable();
            $table->decimal('commission_amount', 20)->nullable();
            $table->decimal('level_revenue', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions_packages');
    }
};
