<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_sales extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'product_sales';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'product_id',
        'user_id',
        'admin_id',
        'quantity',
        'total_price',
        'discount',
        'sale_date',
    ];

    // Definisikan relasi dengan model lain jika diperlukan
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(Users::class, 'admin_id');
    }
}
