<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'reviews';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'reservation_id',
        'user_id',
        'worker_id',
        'rating',
        'feedback',
    ];

    // Relasi dengan model lain (opsional)
    public function reservation()
    {
        return $this->belongsTo(Reservations::class, 'reservation_id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function worker()
    {
        return $this->belongsTo(Users::class, 'worker_id');
    }
}
