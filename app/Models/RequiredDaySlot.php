<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequiredDaySlot extends Model
{
    //
    protected $fillable = ['client_id', 'day', 'start_time', 'end_time', 'slot'];

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
