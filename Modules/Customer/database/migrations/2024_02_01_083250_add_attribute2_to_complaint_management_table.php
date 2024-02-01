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
        Schema::table('complaint_management', function (Blueprint $table) {
            $table->string('code');
            $table->string('resource')->default('hệ thống')->nullable(); // từ app or hệ thống
            $table->string('status')->default('no_process')->nullable();

            $table->unsignedBigInteger('product_service_owner_id')->nullable();
            $table->unsignedBigInteger('counselor_staff_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaint_management', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('resource');
            $table->dropColumn('problem_solution');
            $table->dropColumn('product_service_owner_id');
            $table->dropColumn('counselor_staff_id');
        });
    }
};
