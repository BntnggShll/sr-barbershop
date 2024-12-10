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
        'id_income',
        'id_expense',
        'net_profit',
        'report_date',
        'description',
    ];

    // Relasi dengan model lain (opsional)
    public function income()
    {
        return $this->belongsTo(Income::class,'id_income');
    }
    
    public function expense()
    {
        return $this->belongsTo(Expense::class,'id_expense');
    }

}
