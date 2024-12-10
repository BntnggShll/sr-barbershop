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
        Schema::create('income', function (Blueprint $table) {
            $table->id('id_income');
            $table->foreignId('worker_id')->references('user_id')->on('users')->onDelete('cascade')->nullable();
            $table->foreignId('payment_id')->references('payment_id')->on('payments')->onDelete('cascade')->nullable();
            $table->decimal('income', 15, 0);
            $table->string('description');
            $table->date('report_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income');
    }
};
