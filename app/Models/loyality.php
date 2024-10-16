<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loyality extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'loyalty';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'points_earned',
        'points_redeemed',
        'discount_percentage',
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }
}
