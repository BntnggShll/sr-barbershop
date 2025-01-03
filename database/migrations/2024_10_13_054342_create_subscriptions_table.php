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
        Schema::create('subscriptions', function (Blueprint $table) {
        $table->id('subscription_id');
        $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade')->nullable();
        $table->date('start_date');
        $table->date('end_date');
        $table->integer('price');
        $table->string('description');
        $table->enum('status', ['Active', 'Expired']);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
