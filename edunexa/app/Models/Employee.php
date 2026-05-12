<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'jabatan',
        'divisi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class, 'pegawai_id');
    }
}
