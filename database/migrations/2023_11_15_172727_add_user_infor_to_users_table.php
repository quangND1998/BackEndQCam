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
        Schema::table('users', function (Blueprint $table) {
            $table->text('specific_address')->nullable();
            $table->string("fcm_token")->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('wards')->nullable();
            $table->date('cic_date')->nullable();
            $table->date('cic_date_expried')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('specific_address');
            $table->dropColumn('fcm_token');
            $table->dropColumn('city');
            $table->dropColumn('district');
            $table->dropColumn('wards');
            $table->dropColumn('wards');
            $table->dropColumn('cic_date_expried');
        });
    }
};
