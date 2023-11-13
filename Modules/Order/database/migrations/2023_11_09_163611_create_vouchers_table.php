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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();


            // The voucher code
            $table->string('code')->nullable();

            // The human readable voucher code name
            $table->string('name')->nullable();
            // The description of the voucher - Not necessary
            $table->text('description')->nullable();

            // The number of uses currently
            $table->integer('uses')->unsigned()->nullable();

            // The max uses this voucher has
            $table->integer('max_uses')->unsigned()->nullable();

            // How many times a user can use this voucher.
            $table->integer('max_uses_user')->unsigned()->nullable();

            // The type can be: voucher, discount, sale. What ever you want.
            $table->tinyInteger('type')->unsigned()->nullable();

            // The amount to discount by (in pennies) in this example.


            $table->bigInteger('min_spend')->nullable();
            $table->bigInteger('discount_caption')->default(0)->nullable();
            $table->bigInteger('discount_percentage')->default(0)->nullable();
            $table->bigInteger('discount_value')->default(0)->nullable();
            $table->bigInteger('discount_max_value')->default(0)->nullable();
            // Whether or not the voucher is a percentage or a fixed price. 
            $table->boolean('is_fixed')->default(true);
            // When the voucher begins
            $table->timestamp('starts_at')->nullable();

            // When the voucher ends
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
