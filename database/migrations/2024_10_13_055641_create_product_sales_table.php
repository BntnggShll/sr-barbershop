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
         Schema::create('product_sales', function (Blueprint $table) {
        $table->id('sale_id'); // Primary key
        $table->foreignId('product_id')->references('product_id')->on('products')->onDelete('cascade'); // Menghubungkan dengan tabel produk
        $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->foreignId('admin_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->integer('quantity'); // Jumlah produk yang dijual
        $table->decimal('total_price', 15, 2); // Total harga yang dibayarkan
        $table->decimal('discount', 15, 2)->default(0); // Diskon yang diterapkan, jika ada
        $table->timestamp('sale_date'); // Tanggal transaksi penjualan
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sales');
    }
};
