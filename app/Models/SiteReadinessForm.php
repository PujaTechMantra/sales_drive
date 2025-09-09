<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteReadinessForm extends Model
{
    //
    protected $table = 'site_readiness_form';
    protected $fillable = ['slot_booking_id', 'distributor_code', 'distributor_name', 'full_address', 'office_phone_no',
    'city', 'state', 'zone', 'contact_person','distributor_email', 'contact_person_phone', 'gst_number', 'pan_number', 'so_name', 'so_contact_number',
    'brands', 'beat_name', 'beat_id', 'beat_type', 'region_code','region_csp', 'region_name', 'beat_distributor_codes',
    'employee_id', 'employee_label', 'employee_name', 'designation_code', 'rm_employee_id', 'rm_designation_code', 'state_code', 
    'employee_distributor_codes', 'employee_distributor_mapping', 'dsr_distributor_mapping', 'beat_employee_mapping',
    'supplier_distributor_mapping', 'outlet_sync_csp', 'outlet_lead_creation', 'outlet_lead_approval', 'regional_price',
    'opening_stock', 'grn_invoice', 'sales_order', 'opening_points', 'remarks', 'distributor_code_status', 'distributor_name_status', 'full_address_status', 'office_phone_no_status',
            'city_status', 'state_status', 'zone_status', 'contact_person_status', 'distributor_email_status', 'contact_person_phone_status',
            'gst_number_status', 'pan_number_status', 'so_name_status', 'so_contact_number_status', 'brands_status', 'beat_name_status', 'beat_id_status', 'beat_type_status',
            'region_code_status', 'region_csp_status', 'region_name_status', 'beat_distributor_codes_status', 'employee_id_status', 'employee_label_status', 'employee_name_status',
            'designation_code_status', 'rm_employee_id_status', 'rm_designation_code_status', 'state_code_status', 'employee_distributor_codes_status', 'employee_distributor_mapping_status',
            'dsr_distributor_mapping_status', 'beat_employee_mapping_status', 'supplier_distributor_mapping_status', 'outlet_sync_csp_status', 'outlet_lead_creation_status', 'outlet_lead_approval_status',
            'regional_price_status', 'opening_stock_status', 'grn_invoice_status', 'sales_order_status', 'opening_points_status'];

    public function slotBooking()
    {
        return $this->belongsTo(SlotBooking::class, 'slot_booking_id');
    }
}
