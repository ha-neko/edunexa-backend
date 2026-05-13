<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids; // Import ini

class Attendance extends Model
{
    use HasUlids; // Gunakan ini

    protected $fillable = [
        'user_id',
        'waktu_masuk',
        'waktu_keluar',
        'status',
        'keterangan'
    ];
}