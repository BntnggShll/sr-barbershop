<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    use HasFactory;
    protected $primaryKey ='schedule_id';
    // Nama tabel jika berbeda dari konvensi (jika tabel bernama schedules, tidak perlu ditulis)
    protected $table = 'schedules';

    // Menentukan kolom yang dapat diisi
    // protected $fillable = [
    //     'worker_id',
    //     'available_date',
    //     'available_time_start',
    //     'available_time_end',
    //     'status',
    // ];
    protected $guarded = [];   
    
    public function worker()
    {
        return $this->belongsTo(Users::class, 'worker_id', 'user_id');
    }
}
