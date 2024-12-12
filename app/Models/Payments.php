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
        'user_id',
        'payable',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_date',
    ];

    public function payable()
    {
        return $this->morphTo();
    }


    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }
}
