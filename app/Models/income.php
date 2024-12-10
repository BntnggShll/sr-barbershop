<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    // Nama tabel jika tidak sesuai konvensi Laravel
    protected $table = 'income';

    // Primary key jika tidak menggunakan default 'id'
    protected $primaryKey = 'id_income';

    // Kolom-kolom yang dapat diisi
    protected $fillable = [
        'worker_id',
        'income',
        'description',
        'report_date',
    ];

    /**
     * Relasi ke model User (worker).
     * Menghubungkan kolom worker_id ke user_id di tabel users.
     */
    public function worker()
    {
        return $this->belongsTo(Users::class, 'worker_id', 'user_id');
    }
}
