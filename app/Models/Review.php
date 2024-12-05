<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'reviews';
     
    protected $primaryKey ='review_id';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'reservation_id',
        'user_id',
        'worker_id',
        'rating',
        'feedback',
    ];

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('M d Y');
    }

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
