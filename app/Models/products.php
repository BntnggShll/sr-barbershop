<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'products';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];
}
