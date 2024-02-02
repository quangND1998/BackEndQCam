<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Customer\app\Models\ComplaintManagement;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('problem_solutions', function (Blueprint $table) {
            $table->longText('reason')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('problem_solutions', function (Blueprint $table) {
            $table->dropColumn('reason');
            $table->dropColumn('role_id');
        });
    }

};
