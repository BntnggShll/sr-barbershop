<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   Reservations extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'reservations';

    protected $primaryKey='reservation_id';
    

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'service_id',
        'worker_id',
        'schedule_id',
        'reservation_status',
    ];

    public function payments()
    {
        return $this->morphMany(Payments::class, 'payable');
    }
    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }

    // Relasi dengan model Service
    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id', 'service_id');
    }

    // Relasi dengan model Worker (User)
    public function worker()
    {
        return $this->belongsTo(Users::class, 'worker_id', 'user_id');
    }

    // Relasi dengan model Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedules::class, 'schedule_id', 'schedule_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reservation_id');
    }
}
