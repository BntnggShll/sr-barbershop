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
        Schema::create('reviews', function (Blueprint $table) {
        $table->id('review_id');
        $table->foreignId('reservation_id')->references('reservation_id')->on('reservations')->onDelete('cascade');
        $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->foreignId('worker_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->integer('rating')->default(0);
        $table->text('feedback')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
