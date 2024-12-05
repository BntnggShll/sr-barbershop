<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\Worker;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // Mengatur nama tabel jika berbeda dengan konvensi
    protected $table = 'users';

    // Mengatur primary key
    protected $primaryKey ='user_id';
    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    // Mengatur atribut yang dapat diisi
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'subscription_status',
        'points',
        'google_id',
        'image',
    ];

    // Mengatur atribut yang harus di-hash
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mengatur tipe atribut untuk casting
    protected $casts = [
        'points' => 'integer',
    ];
    public function jadwal()
    {
        return $this->belongsTo(Schedules::class,'worker_id','user_id');
    }
    public function reservation()
    {
        return $this->hasManyThrough(
            Services::class, // Model akhir yang ingin diakses
            Reservations::class, // Model perantara
            'user_id', // Foreign key di TableC (relasi ke TableA)
            'service_id', // Foreign key di TableD (relasi ke TableC)
            'user_id',            // Local key di TableA
            'reservation_id',             // Local key di TableC
        );
    }
    

}
