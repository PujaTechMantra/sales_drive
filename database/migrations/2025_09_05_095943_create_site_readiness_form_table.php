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
        Schema::create('site_readiness_form', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slot_booking_id');

            // -------- Distributor Information --------
            $table->string('distributor_code')->nullable(); // SAP code
            $table->string('distributor_name')->nullable();
            $table->string('full_address')->nullable();
            $table->string('office_phone_no')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zone')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('distributor_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('so_name')->nullable();
            $table->string('so_contact_number')->nullable();

            // -------- Master Data Requirement --------
            $table->text('brands')->nullable(); // Mention all brands
            // Beat Creation
            $table->string('beat_name')->nullable();
            $table->string('beat_id')->nullable();
            $table->string('beat_type')->nullable();
            $table->string('region_code')->nullable();
            $table->string('region_csp')->nullable();
            $table->string('region_name')->nullable();
            $table->string('beat_distributor_codes')->nullable();

            // Employee List Creation
            $table->string('employee_id')->nullable(); // Label of SAP
            $table->string('employee_label')->nullable(); // ID of SAP
            $table->string('employee_name')->nullable();
            $table->string('designation_code')->nullable(); // CSP
            $table->string('rm_employee_id')->nullable();
            $table->string('rm_designation_code')->nullable();
            $table->string('state_code')->nullable();
            $table->string('employee_distributor_codes')->nullable();

            // -------- Mappings --------
            $table->string('employee_distributor_mapping')->nullable();
            $table->string('dsr_distributor_mapping')->nullable();
            $table->string('beat_employee_mapping')->nullable();
            $table->string('supplier_distributor_mapping')->nullable();

            // -------- Outlet --------
            $table->string('outlet_sync_csp')->nullable();
            $table->string('outlet_lead_creation')->nullable();
            $table->string('outlet_lead_approval')->nullable();

            // -------- Regional Price --------
            $table->string('regional_price')->nullable();

            // -------- Opening Stock / Orders --------
            $table->string('opening_stock')->nullable();
            $table->string('grn_invoice')->nullable();
            $table->string('sales_order')->nullable();
            $table->string('opening_points')->nullable();

            // -------- Extra --------
            $table->text('remarks')->nullable();

            $table->tinyInteger('status')->default(1)->comment('1: active | 0: inactive');

            $table->timestamps();

            $table->foreign('slot_booking_id')
                ->references('id')->on('slot_bookings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_readiness_form');
    }
};
