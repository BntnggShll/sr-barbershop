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
        'worker_id',
        'total_income',
        'total_expense',
        'net_profit',
        'report_date',
        'description',
    ];

    // Relasi dengan model lain (opsional)
    public function worker()
    {
        return $this->belongsTo(Users::class, 'worker_id','user_id');
    }
}
