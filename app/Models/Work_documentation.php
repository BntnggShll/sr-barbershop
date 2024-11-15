<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_documentation extends Model
{
    use HasFactory;

    protected $table = 'work_documentation';

    protected $primaryKey ='documentation_id';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'worker_id',
        'reservation_id',
        'photo_url',
        'description',
    ];

    // Relasi ke tabel users
    public function worker()
    {
        return $this->belongsTo(Users::class, 'worker_id', 'user_id');
    }
    public function reservation()
    {
        return $this->belongsTo(Reservations::class, 'reservation_id', 'reservation_id');
    }
}
