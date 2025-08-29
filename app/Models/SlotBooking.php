<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotBooking extends Model
{
    //
    protected $fillable = ['client_id', 'distributor_name', 'distributor_address', 'distributor_contact_no', 
    'distributor_email', 'slot_date', 'status', 'remarks'];

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
        
}
