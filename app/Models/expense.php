<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    // Nama tabel jika tidak sesuai konvensi Laravel
    protected $table = 'expense';

    // Primary key jika tidak menggunakan default 'id'
    protected $primaryKey = 'id_expense';

    // Kolom-kolom yang dapat diisi
    protected $fillable = [
        'admin_id',
        'expense',
        'description',
        'report_date',
    ];

    /**
     * Relasi ke model User (admin).
     * Menghubungkan kolom admin_id ke user_id di tabel users.
     */
    public function admin()
    {
        return $this->belongsTo(Users::class, 'admin_id', 'user_id');
    }
}
