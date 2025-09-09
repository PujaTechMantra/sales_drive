<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotBooking extends Model
{
    //
    protected $fillable = ['client_id','site_ready', 'training_done', 'distributor_name', 'distributor_address', 'distributor_contact_no', 
    'distributor_email', 'gst_number', 'pan_number', 'slot_date', 'status', 'remarks', 'training_remarks', 'distributor_code',
    'city', 'state', 'zone', 'distributor_contact_person', 'distributor_contact_person_phone', 'so_name', 'so_contact_no'];

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function siteReadinessForm()
    {
        return $this->hasOne(SiteReadinessForm::class, 'slot_booking_id');
    }
        
}
