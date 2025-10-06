<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotBooking extends Model
{
    //
    protected $fillable = ['client_id','site_ready', 'training_done', 'distributor_name', 'distributor_address', 'distributor_contact_no', 
    'distributor_email', 'gst_number', 'pan_number', 'slot_date','slot_date_1st', 'status', 'remarks', 'training_remarks', 'distributor_code',
    'city', 'state', 'zone', 'distributor_contact_person', 'distributor_contact_person_phone', 'so_name', 'so_contact_no',
    'slot_start_time', 'slot_end_time', 'complete_status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function siteReadinessForm()
    {
        return $this->hasOne(SiteReadinessForm::class, 'slot_booking_id');
    }
        
    // SlotBooking.php
    public function reschedules()
    {
            return $this->hasMany(UserActivityLog::class, 'user_id', 'client_id')
                ->where(function ($query) {
                    $query->where('action', 'slot_rescheduled');
            
                })
                ->when($this->distributor_code, function ($query) {
                    $query->whereJsonContains('user_data->distributor_code', $this->distributor_code);
                })
                ->orderBy('created_at', 'asc');
    }




}
