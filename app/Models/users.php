<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    // Mengatur nama tabel jika berbeda dengan konvensi
    protected $table = 'users';

    // Mengatur primary key
    protected $primaryKey = 'user_id';

    // Mengatur atribut yang dapat diisi
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'subscription_status',
        'points',
        'google_id'
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
}
