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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('qr_code')->nullable();
            $table->unsignedBigInteger('land_id')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('price')->nullable();
            $table->text('state')->nullable();
            $table->string('status')->nullable();
            $table->longText('description')->nullable();
            $table->longText('user_manual')->nullable();
            $table->longText('terms_policy')->nullable();
            $table->unsignedBigInteger('product_service_owner_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};
