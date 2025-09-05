<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteReadinessForm extends Model
{
    //
    protected $table = 'site_readiness_form';
    protected $fillable = ['slot_booking_id', 'distributor_code', 'distributor_name', 'full_address', 'office_phone_no',
    'city', 'state', 'zone', 'contact_person', 'contact_person_phone', 'gst_number', 'pan_number', 'so_name', 'so_contact_number',
    'brands', 'beat_name', 'beat_id', 'beat_type', 'region_code', 'region_name', 'beat_distributor_codes',
    'employee_id', 'employee_label', 'employee_name', 'designation_code', 'rm_employee_id', 'rm_designation_code', 'state_code', 
    'employee_distributor_codes', 'employee_distributor_mapping', 'dsr_distributor_mapping', 'beat_employee_mapping',
    'supplier_distributor_mapping', 'outlet_sync_csp', 'outlet_lead_creation', 'outlet_lead_approval', 'regional_price',
    'opening_stock', 'grn_invoice', 'sales_order', 'opening_points', 'remarks'];

    public function slotBooking()
    {
        return $this->belongsTo(SlotBooking::class, 'slot_booking_id');
    }
}
