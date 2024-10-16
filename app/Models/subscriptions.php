<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'subscriptions';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'price',
        'status',
    ];

    // Relasi dengan model lain (opsional)
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
