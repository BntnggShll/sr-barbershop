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
         Schema::create('reservations', function (Blueprint $table) {
        $table->id('reservation_id');
        $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade')->nullable();
        $table->foreignId('service_id')->references('service_id')->on('services')->onDelete('cascade')->nullable();
        $table->foreignId('worker_id')->references('user_id')->on('users')->onDelete('cascade')->nullable();
        $table->foreignId('schedule_id')->references('schedule_id')->on('schedules')->onDelete('cascade')->nullable();
        $table->enum('reservation_status', ['Pending', 'Confirmed', 'Completed', 'Canceled']);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
