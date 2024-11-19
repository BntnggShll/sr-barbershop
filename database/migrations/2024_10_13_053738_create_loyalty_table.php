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
        Schema::create('loyalty', function (Blueprint $table) {
        $table->id('loyalty_id');
        $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade')->nullable();
        $table->integer('points_earned');
        $table->integer('points_redeemed')->default(0);
        $table->integer('discount_percentage')->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty');
    }
};
