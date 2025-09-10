<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_readiness_form', function (Blueprint $table) {
            //
            $table->text('distributor_name_remarks')->nullable()->after('distributor_name');
            $table->text('distributor_code_remarks')->nullable()->after('distributor_code'); // SAP code
            $table->text('full_address_remarks')->nullable()->after('full_address');
            $table->text('office_phone_no_remarks')->nullable()->after('office_phone_no');
            $table->text('city_remarks')->nullable()->after('city');
            $table->text('state_remarks')->nullable()->after('state');
            $table->text('zone_remarks')->nullable()->after('zone');
            $table->text('contact_person_remarks')->nullable()->after('contact_person');
            $table->text('distributor_email_remarks')->nullable()->after('distributor_email');
            $table->text('contact_person_phone_remarks')->nullable()->after('contact_person_phone');
            $table->text('gst_number_remarks')->nullable()->after('gst_number');
            $table->text('pan_number_remarks')->nullable()->after('pan_number');
            $table->text('so_name_remarks')->nullable()->after('so_name');
            $table->text('so_contact_number_remarks')->nullable()->after('so_contact_number');

            // -------- Master Data Requirement --------
            $table->text('brands_remarks')->nullable()->after('brands'); // Mention all brands
            // Beat Creation
            $table->text('beat_name_remarks')->nullable()->after('beat_name');
            $table->text('beat_id_remarks')->nullable()->after('beat_id');
            $table->text('beat_type_remarks')->nullable()->after('beat_type');
            $table->text('region_code_remarks')->nullable()->after('region_code');
            $table->text('region_csp_remarks')->nullable()->after('region_csp');
            $table->text('region_name_remarks')->nullable()->after('region_name');
            $table->text('beat_distributor_codes_remarks')->nullable()->after('beat_distributor_codes');

            // Employee List Creation
            $table->text('employee_id_remarks')->nullable()->after('employee_id'); // Label of SAP
            $table->text('employee_label_remarks')->nullable()->after('employee_label'); // ID of SAP
            $table->text('employee_name_remarks')->nullable()->after('employee_name');
            $table->text('designation_code_remarks')->nullable()->after('designation_code'); // CSP
            $table->text('rm_employee_id_remarks')->nullable()->after('rm_employee_id');
            $table->text('rm_designation_code_remarks')->nullable()->after('rm_designation_code');
            $table->text('state_code_remarks')->nullable()->after('state_code');
            $table->text('employee_distributor_codes_remarks')->nullable()->after('employee_distributor_codes');

            // -------- Mappings --------
            $table->text('employee_distributor_mapping_remarks')->nullable()->after('employee_distributor_mapping');
            $table->text('dsr_distributor_mapping_remarks')->nullable()->after('dsr_distributor_mapping');
            $table->text('beat_employee_mapping_remarks')->nullable()->after('beat_employee_mapping');
            $table->text('supplier_distributor_mapping_remarks')->nullable()->after('supplier_distributor_mapping');

            // -------- Outlet --------
            $table->text('outlet_sync_csp_remarks')->nullable()->after('outlet_sync_csp');
            $table->text('outlet_lead_creation_remarks')->nullable()->after('outlet_lead_creation');
            $table->text('outlet_lead_approval_remarks')->nullable()->after('outlet_lead_approval');

            // -------- Regional Price --------
            $table->text('regional_price_remarks')->nullable()->after('regional_price');

            // -------- Opening Stock / Orders --------
            $table->text('opening_stock_remarks')->nullable()->after('opening_stock');
            $table->text('grn_invoice_remarks')->nullable()->after('grn_invoice');
            $table->text('sales_order_remarks')->nullable()->after('sales_order');
            $table->text('opening_points_remarks')->nullable()->after('opening_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_readiness_form', function (Blueprint $table) {
            //
        });
    }
};
