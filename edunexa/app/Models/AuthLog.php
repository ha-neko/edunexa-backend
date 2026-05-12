<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id', 'event', 'ip_address',
    ];

    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
