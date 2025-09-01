<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotBooking extends Model
{
    //
    protected $fillable = ['client_id','site_ready', 'training_done', 'distributor_name', 'distributor_address', 'distributor_contact_no', 
    'distributor_email', 'slot_date', 'status', 'remarks', 'training_remarks'];

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
        
}
