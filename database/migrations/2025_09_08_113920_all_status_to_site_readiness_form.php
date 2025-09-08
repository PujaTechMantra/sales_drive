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
            $table->tinyInteger('distributor_name_status')->default(1)->after('distributor_name');
            $table->tinyInteger('distributor_code_status')->default(1)->after('distributor_code'); // SAP code
            $table->tinyInteger('full_address_status')->default(1)->after('full_address');
            $table->tinyInteger('office_phone_no_status')->default(1)->after('office_phone_no');
            $table->tinyInteger('city_status')->default(1)->after('city');
            $table->tinyInteger('state_status')->default(1)->after('state');
            $table->tinyInteger('zone_status')->default(1)->after('zone');
            $table->tinyInteger('contact_person_status')->default(1)->after('contact_person');
            $table->tinyInteger('distributor_email_status')->default(1)->after('distributor_email');
            $table->tinyInteger('contact_person_phone_status')->default(1)->after('contact_person_phone');
            $table->tinyInteger('gst_number_status')->default(1)->after('gst_number');
            $table->tinyInteger('pan_number_status')->default(1)->after('pan_number');
            $table->tinyInteger('so_name_status')->default(1)->after('so_name');
            $table->tinyInteger('so_contact_number_status')->default(1)->after('so_contact_number');

            // -------- Master Data Requirement --------
            $table->tinyInteger('brands_status')->default(1)->after('brands'); // Mention all brands
            // Beat Creation
            $table->tinyInteger('beat_name_status')->default(1)->after('beat_name');
            $table->tinyInteger('beat_id_status')->default(1)->after('beat_id');
            $table->tinyInteger('beat_type_status')->default(1)->after('beat_type');
            $table->tinyInteger('region_code_status')->default(1)->after('region_code');
            $table->tinyInteger('region_csp_status')->default(1)->after('region_csp');
            $table->tinyInteger('region_name_status')->default(1)->after('region_name');
            $table->tinyInteger('beat_distributor_codes_status')->default(1)->after('beat_distributor_codes');

            // Employee List Creation
            $table->tinyInteger('employee_id_status')->default(1)->after('employee_id'); // Label of SAP
            $table->tinyInteger('employee_label_status')->default(1)->after('employee_label'); // ID of SAP
            $table->tinyInteger('employee_name_status')->default(1)->after('employee_name');
            $table->tinyInteger('designation_code_status')->default(1)->after('designation_code'); // CSP
            $table->tinyInteger('rm_employee_id_status')->default(1)->after('rm_employee_id');
            $table->tinyInteger('rm_designation_code_status')->default(1)->after('rm_designation_code');
            $table->tinyInteger('state_code_status')->default(1)->after('state_code');
            $table->tinyInteger('employee_distributor_codes_status')->default(1)->after('employee_distributor_codes');

            // -------- Mappings --------
            $table->tinyInteger('employee_distributor_mapping_status')->default(1)->after('employee_distributor_mapping');
            $table->tinyInteger('dsr_distributor_mapping_status')->default(1)->after('dsr_distributor_mapping');
            $table->tinyInteger('beat_employee_mapping_status')->default(1)->after('beat_employee_mapping');
            $table->tinyInteger('supplier_distributor_mapping_status')->default(1)->after('supplier_distributor_mapping');

            // -------- Outlet --------
            $table->tinyInteger('outlet_sync_csp_status')->default(1)->after('outlet_sync_csp');
            $table->tinyInteger('outlet_lead_creation_status')->default(1)->after('outlet_lead_creation');
            $table->tinyInteger('outlet_lead_approval_status')->default(1)->after('outlet_lead_approval');

            // -------- Regional Price --------
            $table->tinyInteger('regional_price_status')->default(1)->after('regional_price');

            // -------- Opening Stock / Orders --------
            $table->tinyInteger('opening_stock_status')->default(1)->after('opening_stock');
            $table->tinyInteger('grn_invoice_status')->default(1)->after('grn_invoice');
            $table->tinyInteger('sales_order_status')->default(1)->after('sales_order');
            $table->tinyInteger('opening_points_status')->default(1)->after('opening_points');
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
