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
        Schema::create('work_documentation', function (Blueprint $table) {
        $table->id('documentation_id');
        $table->foreignId('worker_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->foreignId('reservation_id')->references('reservation_id')->on('reservations')->onDelete('cascade');
        $table->string('photo_url');
        $table->text('description')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_documentation');
    }
};
