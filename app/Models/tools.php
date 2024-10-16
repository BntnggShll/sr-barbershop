<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'tools';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'tool_name',
        'quantity',
        'description',
        'category',
    ];
}
