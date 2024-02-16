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
        Schema::create('history_calls', function (Blueprint $table) {
            $table->id();
            $table->string('call_id');
            $table->string('status');
            $table->string('cause');
            $table->float('duration');
            $table->string('direction'); //inbound or outbound
            // recording_url
            $table->integer('extension');
            $table->integer('from_number');
            $table->integer('to_number');
            $table->integer('receive_dest')->nullable();
            $table->timestampTz('time_started')->nullable();
            $table->timestampTz('time_answered')->nullable();
            $table->timestampTz('time_ended')->nullable();
            $table->timestampTz('time_ringging')->nullable();
            $table->integer('billsec');
            $table->integer('called_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_calls');
    }
};
