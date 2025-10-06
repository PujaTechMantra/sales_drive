<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'action',
        'user_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'user_data' => 'array', // automatically cast JSON to array
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
