<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'pegawai_id',
        'model_kendaraan',
        'jenis_kendaraan',
        'nomor_kendaraan',
        'sim',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'pegawai_id');
    }
}
