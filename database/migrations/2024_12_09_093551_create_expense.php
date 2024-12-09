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
        Schema::create('expense', function (Blueprint $table) {
            $table->id('id_expense');
            $table->foreignId('admin_id')->references('user_id')->on('users')->onDelete('cascade')->nullable();
            $table->integer('expense');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense');
    }
};
