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
        Schema::table('commissions', function (Blueprint $table) {
            $table->decimal('level_revenue', 30, 2)->nullable();
            $table->decimal('discount_form_sale', 30, 2)->nullable();
            $table->decimal('discount_form_manager_sale', 30, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropColumn('level_revenue');
            $table->dropColumn('discount_form_sale', 30, 2)->nullable();
            $table->dropColumn('discount_form_manager_sale', 30, 2)->nullable();
        });
    }
};