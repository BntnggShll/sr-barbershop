<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->decimal('total_income', 15, 2); // Total pemasukan
            $table->foreignId('id_expense')->references('id_expense')->on('expense')->onDelete('cascade')->nullable(); // Total pengeluaran
            $table->decimal('net_profit', 15, 2)->nullable(); // Laba bersih (pemasukan - pengeluaran)
            $table->date('report_date'); // Tanggal laporan
            $table->text('description')->nullable(); // Deskripsi tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
