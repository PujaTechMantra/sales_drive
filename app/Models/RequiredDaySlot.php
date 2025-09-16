<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequiredDaySlot extends Model
{
    //
    protected $fillable = ['client_id', 'day', 'slot'];
}
