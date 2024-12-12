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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->morphs('payable');
            $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade')->nullable();
            $table->decimal('amount', 8, 2);
            $table->enum('payment_method', ['Credit Card', 'E-Wallet']);
            $table->enum('payment_status', ['Pending', 'Completed', 'Failed']);
            $table->timestamp('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
