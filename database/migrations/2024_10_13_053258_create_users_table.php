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
        Schema::create('users', function (Blueprint $table) {
        $table->id('user_id');
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('phone_number')->nullable();
        $table->enum('role', ['User', 'Pekerja', 'Admin']);
        $table->enum('subscription_status', ['Aktif', 'Tidak Aktif'])->default('Tidak Aktif');
        $table->integer('points')->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
