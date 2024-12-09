<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial_reports extends Model
{
    use HasFactory;

    // Nama tabel jika berbeda dari konvensi
    protected $table = 'financial_reports';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'total_income',
        'id_expense',
        'net_profit',
        'report_date',
        'description',
    ];

    // Relasi dengan model lain (opsional)
    public function admin()
    {
        return $this->belongsTo(Users::class, 'admin_id');
    }
}
