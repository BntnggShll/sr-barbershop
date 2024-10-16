<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'payments';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'reservation_id',
        'user_id',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_date',
    ];

    // Relasi dengan model Reservation
    public function reservation()
    {
        return $this->belongsTo(Reservations::class, 'reservation_id', 'reservation_id');
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }
}
