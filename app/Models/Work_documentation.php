<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_documentation extends Model
{
    use HasFactory;

    protected $table = 'work_documentation';

    // Menentukan kolom yang dapat diisi
    protected $fillable = [
        'worker_id',
        'reservation_id',
        'photo_url',
        'description',
    ];
}
