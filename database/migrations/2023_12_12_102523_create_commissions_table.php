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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('spend_from', 30, 2)->nullable();
            $table->decimal('spend_to', 30, 2)->nullable();
            $table->decimal('commission', 30, 2)->nullable();
            $table->decimal('level_revenue', 30, 2)->nullable();
            $table->string('type')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('greater')->default(true);
            $table->boolean('less')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
