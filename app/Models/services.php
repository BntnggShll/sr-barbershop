<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'services';

    // Kolom-kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'service_name',
        'description',
        'price',
        'duration',
        'image'
    ];

    // Secara opsional, bisa ditambahkan relasi jika ada, misalnya dengan reservasi:
    public function reservations()
    {
        return $this->hasMany(Reservations::class, 'service_id', 'service_id');
    }
}
