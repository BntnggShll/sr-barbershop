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
         Schema::create('schedules', function (Blueprint $table) {
        $table->id('schedule_id');
        $table->foreignId('worker_id')->references('user_id')->on('users')->onDelete('cascade')->nullable();
        $table->date('available_date');
        $table->time('available_time_start');
        $table->time('available_time_end');
        $table->enum('status', ['Available', 'Booked', 'Unavailable']);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
