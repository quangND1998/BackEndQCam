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
        Schema::create('product_fails', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_priority')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('quality')->nullable();
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('product_retail_id')->nullable();
            $table->unsignedBigInteger('user_add')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_fails');
    }
};
