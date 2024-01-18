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
        Schema::table('product_retails', function (Blueprint $table) {
            $table->integer('quantity')->after('price')->default(0);
            $table->string('unit')->after('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_retails', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('unit');
        });
    }
};
