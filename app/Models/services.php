<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'services';
    protected $primaryKey = 'service_id';

    // Kolom-kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'service_name',
        'description',
        'price',
        'duration',
        'image',
    ];

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
    public function reservation()
    {
        return $this->hasMany(Reservations::class, 'service_id');
    }

}
