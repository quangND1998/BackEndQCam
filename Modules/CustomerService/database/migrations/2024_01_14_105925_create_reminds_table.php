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
        Schema::create('reminds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID of CS
            $table->unsignedBigInteger('product_service_owner_id');
            $table->date('remind_at');
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminds');
    }
};
