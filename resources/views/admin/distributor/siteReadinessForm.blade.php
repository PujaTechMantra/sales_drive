@extends('layouts/contentNavbarLayout')

@section('title', 'SiteReadiness Form')

@section('content')
    <style>
        .card-header .btn {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
<div class="container my-5">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center rounded-top-4 py-2">
            <h4 class="fw-bold mb-0">
                Pre-Implementation checklist (Site Readiness) - Preparation for installation by Tech Mantra
            </h4>

            <a href="{{ route('admin.slot-booking.distributorList') }}" class="btn btn-sm btn-danger">
                <i class="menu-icon tf-icons ri-arrow-left-line"></i> Back
            </a>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.client.storeSiteReadiness')}}">
                @csrf
                <input type="hidden" name="slot_booking_id" value="{{ $slot->id }}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped align-middle">
                        <thead class="table">
                            <tr>
                                <th style="width:30%">Section</th>
                                <th style="width:20%">Field</th>
                                <th style="width:20%">Input</th>
                                <th style="width:20%">Remarks</th>
                                <th style="width:10%">checklist</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Distributor Information --}}
                            <tr class="table-primary">
                                <td colspan="5" class="fw-semibold text-center"><strong>Distributor Information</strong></td>
                            </tr>

                            <tr>
                                <td rowspan="14" class="align-middle fw-semibold bg-light-subtle">Distributor Creation</td>
                                <td>Distributor Code (SAP)</td>
                                <td><input type="text" name="distributor_code" class="form-control" value="{{ ucwords($slot->distributor_code) }}" readonly></td>
                                <td>
                                    <input type="text" name="distributor_code_remarks" 
                                        value="{{ optional($siteReady)->distributor_code_remarks }}" class="form-control">        
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->distributor_code_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'distributor_code_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Distributor Name</td>
                                <td><input type="text" name="distributor_name" class="form-control" value="{{ ucwords($slot->distributor_name) }}" readonly></td>
                                <td>
                                    <input type="text" name="distributor_name_remarks" 
                                        value="{{ optional($siteReady)->distributor_name_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->distributor_name_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'distributor_name_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Full Address</td>
                                <td><textarea name="full_address" class="form-control" readonly> {{ ucwords($slot->distributor_address)}}</textarea></td>
                                <td>
                                    <input type="text" name="full_address_remarks" 
                                        value="{{ optional($siteReady)->full_address_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->full_address_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'full_address_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Distributor Phone no</td>
                                <td><input type="text" name="office_phone_no_status" class="form-control" value="{{ $slot->distributor_contact_no }}" readonly></td>
                                <td>
                                    <input type="text" name="office_phone_no_remarks" 
                                        value="{{ optional($siteReady)->office_phone_no_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->office_phone_no_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'office_phone_no_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>Distributor Email ID</td>
                                <td><input type="text" name="distributor_email" class="form-control" value="{{ $slot->distributor_email }}" readonly></td>
                                <td>
                                    <input type="text" name="distributor_email_remarks" 
                                        value="{{ optional($siteReady)->distributor_email_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->distributor_email_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'distributor_email_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>City</td>
                                <td><input type="text" name="city" class="form-control" value="{{ ucwords($slot->city) }}" readonly></td>
                                <td>
                                    <input type="text" name="city_remarks" 
                                        value="{{ optional($siteReady)->city_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->city_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'city_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>State</td>
                                <td><input type="text" name="state" class="form-control" value="{{ ucwords($slot->state) }}" readonly></td>
                                <td>
                                    <input type="text" name="state_remarks" 
                                        value="{{ optional($siteReady)->state_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->state_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'state_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Zone</td>
                                <td><input type="text" name="zone" class="form-control" value="{{ ucwords($slot->zone) }}" readonly></td>
                                <td>
                                    <input type="text" name="zone_remarks" 
                                        value="{{ optional($siteReady)->zone_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->zone_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'zone_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>GST</td>
                                <td><input type="text" name="gst_number" class="form-control" value="{{ $slot->gst_number}}"></td>
                                <td>
                                    <input type="text" name="gst_number_remarks" 
                                        value="{{ optional($siteReady)->gst_number_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->gst_number_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'gst_number_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>PAN</td>
                                <td><input type="text" name="pan_number" class="form-control" value="{{ $slot->pan_number }}"></td>
                                <td>
                                    <input type="text" name="pan_number_remarks" 
                                        value="{{ optional($siteReady)->pan_number_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->pan_number_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'pan_number_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Distributor Contact Person (Optional)</td>
                                <td><input type="text" name="contact_person" class="form-control" value="{{ ucwords($slot->distributor_contact_person) }}" readonly></td>
                                <td>
                                    <input type="text" name="contact_person_remarks" 
                                        value="{{ optional($siteReady)->contact_person_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->contact_person_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'contact_person_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Distributor Contact Person Phone no (Optional)</td>
                                <td><input type="text" name="contact_person_phone" class="form-control" value="{{ ucwords($slot->distributor_contact_person_phone) }}" readonly></td>
                                <td>
                                    <input type="text" name="contact_person_phone_remarks" 
                                        value="{{ optional($siteReady)->contact_person_phone_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->contact_person_phone_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'contact_person_phone_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>SO Name (Optional)</td>
                                <td><input type="text" name="so_name" class="form-control" value="{{ ucwords($slot->so_name) }}" readonly></td>
                                <td>
                                    <input type="text" name="so_name_remarks" 
                                        value="{{ optional($siteReady)->so_name_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->so_name_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'so_name_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>SO Contact Number (Optional)</td>
                                <td><input type="text" name="so_contact_number" class="form-control" value="{{ ucwords($slot->so_contact_no) }}" readonly></td>
                                <td>
                                    <input type="text" name="so_contact_number_remarks" 
                                        value="{{ optional($siteReady)->so_contact_number_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->so_contact_number_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'so_contact_number_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            {{-- Master Data Requirement --}}
                            <tr class="table-primary">
                                <td colspan="5" class="fw-semibold text-center">Master Data Requirement</td>
                            </tr>
                            <tr>
                                <td class="align-middle fw-semibold bg-light-subtle">Brand Mapping</td>
                                <td>Mention all Brands</td>
                                <td><input type="text" name="brands" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="brands_remarks" 
                                        value="{{ optional($siteReady)->brands_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->brands_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'brands_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="6" class="align-middle fw-semibold bg-light-subtle">Beat Creation</td>
                                <td>Beat Name</td>
                                <td><input type="text" name="beat_name" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="beat_name_remarks" 
                                        value="{{ optional($siteReady)->beat_name_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->beat_name_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'beat_name_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Beat ID</td>
                                <td><input type="text" name="beat_id" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="beat_id_remarks" 
                                        value="{{ optional($siteReady)->beat_id_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->beat_id_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'beat_id_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Beat Type - Normal/Split</td>
                                <td>
                                    <input type="text" name="beat_type" class="form-control" readonly>
                                </td>
                                <td>
                                    <input type="text" name="beat_type_remarks" 
                                        value="{{ optional($siteReady)->beat_type_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->beat_type_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'beat_type_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Region Code </td>
                                <td><input type="text" name="region_code" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="region_code_remarks" 
                                        value="{{ optional($siteReady)->region_code_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->region_code_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'region_code_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Region Name- In CSP</td>
                                <td><input type="text" name="region_name" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="region_name_remarks" 
                                        value="{{ optional($siteReady)->region_name_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->region_name_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'region_name_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Distributor Codes</td>
                                <td><input type="text" name="beat_distributor_codes" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="beat_distributor_codes_remarks" 
                                        value="{{ optional($siteReady)->beat_distributor_codes_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->beat_distributor_codes_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'beat_distributor_codes_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            {{-- Employee List Creation --}}
                            <tr>
                                <td rowspan="8" class="align-middle fw-semibold bg-light-subtle">Employee List Creation</td>
                                <td>Employee ID</td>
                                <td><input type="text" name="employee_id" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="employee_id_remarks" 
                                        value="{{ optional($siteReady)->employee_id_remarks }}" class="form-control"> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->employee_id_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'employee_id_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Employee Label</td>
                                <td><input type="text" name="employee_label" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="employee_label_remarks" 
                                        value="{{ optional($siteReady)->employee_label_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->employee_label_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'employee_label_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Employee Name</td>
                                <td><input type="text" name="employee_name" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="employee_name_remarks" 
                                        value="{{ optional($siteReady)->employee_name_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->employee_name_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'employee_name_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Designation Code- CSP</td>
                                <td><input type="text" name="designation_code" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="designation_code_remarks" 
                                        value="{{ optional($siteReady)->designation_code_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->designation_code_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'designation_code_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>RM Employee ID</td>
                                <td><input type="text" name="rm_employee_id" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="rm_employee_id_remarks" 
                                        value="{{ optional($siteReady)->rm_employee_id_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->rm_employee_id_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'rm_employee_id_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>RM Designation Code</td>
                                <td><input type="text" name="rm_designation_code" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="rm_designation_code_remarks" 
                                        value="{{ optional($siteReady)->rm_designation_code_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->rm_designation_code_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'rm_designation_code_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>State Code</td>
                                <td><input type="text" name="state_code" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="state_code_remarks" 
                                        value="{{ optional($siteReady)->state_code_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->state_code_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'state_code_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Distributor Codes</td>
                                <td><input type="text" name="employee_distributor_codes" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="employee_distributor_codes_remarks" 
                                        value="{{ optional($siteReady)->employee_distributor_codes_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->employee_distributor_codes_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'employee_distributor_codes_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Employee-Distributor Mapping</td>
                                <td><input type="text" name="employee_distributor_mapping" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="employee_distributor_mapping_remarks" 
                                        value="{{ optional($siteReady)->employee_distributor_mapping_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->employee_distributor_mapping_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'employee_distributor_mapping_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            {{-- Employee-Distributor Mapping --}}
                            <tr>
                                <td>DSR-Distributor Mapping</td>
                                <td></td>
                                <td><input type="text" name="dsr_distributor_mapping" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="dsr_distributor_mapping_remarks" 
                                        value="{{ optional($siteReady)->dsr_distributor_mapping_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->dsr_distributor_mapping_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'dsr_distributor_mapping_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Beat-Employee Mapping</td>
                                <td></td>
                                <td><input type="text" name="beat_employee_mapping" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="beat_employee_mapping_remarks" 
                                        value="{{ optional($siteReady)->beat_employee_mapping_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->beat_employee_mapping_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'beat_employee_mapping_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                        
                            <tr>
                                <td>Supplier- Distributor Mapping</td>
                                <td></td>
                                <td><input type="text" name="supplier_distributor_mapping" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="supplier_distributor_mapping_remarks" 
                                        value="{{ optional($siteReady)->supplier_distributor_mapping_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->supplier_distributor_mapping_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'supplier_distributor_mapping_status']) }}', this)">
                                    </div>
                                </td>
                            
                            </tr>
                            <tr>
                                <td 
                                rowspan="1" class="align-middle fw-semibold bg-light-subtle">Outlet Automated</td>
                            
                                <td>Sync in CSP</td>
                                <td><input type="text" name="outlet_sync_csp" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="outlet_sync_csp_remarks" 
                                        value="{{ optional($siteReady)->outlet_sync_csp_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->outlet_sync_csp_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'outlet_sync_csp_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            {{-- Outlet Manual Creation --}}
                            <tr>
                                <td rowspan="2" class="align-middle fw-semibold bg-light-subtle">Outlet Manual Creation (optional)</td>
                                <td>Outlet Lead Creation</td>
                                <td><input type="text" name="outlet_lead_creation" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="outlet_lead_creation_remarks" 
                                        value="{{ optional($siteReady)->outlet_lead_creation_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->outlet_lead_approval_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'outlet_lead_creation_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Outlet Lead Approval</td>
                                <td><input type="text" name="outlet_lead_approval" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="outlet_lead_approval_remarks" 
                                        value="{{ optional($siteReady)->outlet_lead_approval_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->outlet_lead_approval_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'outlet_lead_approval_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            {{-- Regional Price --}}
                            <tr>
                                <td>Price: Regional/National</td>
                                <td></td>
                                <td><input type="text" name="regional_price" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="regional_price_remarks" 
                                        value="{{ optional($siteReady)->regional_price_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->regional_price_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'regional_price_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            {{-- Opening Stock --}}
                            <tr>
                                <td>Opening Stock</td>
                                <td>Has the Distributor been informed to provide the opening stocks before the day</td>
                                <td><input type="text" name="opening_stock" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="opening_stock_remarks" 
                                        value="{{ optional($siteReady)->opening_stock_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->opening_stock_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'opening_stock_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            {{-- GRN/Invoice --}}
                            <tr>
                                <td>GRN/Invoice</td>
                                <td>Is GRN Displaying correctly in system?</td>
                                <td><input type="text" name="grn_invoice" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="grn_invoice_remarks" 
                                        value="{{ optional($siteReady)->grn_invoice_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->grn_invoice_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'grn_invoice_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            {{-- Sales Order --}}
                            <tr>
                                <td>Sales Order</td>
                                <td>Is Sales Order Displaying correctly from</td>
                                <td><input type="text" name="sales_order" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="sales_order_remarks" 
                                        value="{{ optional($siteReady)->sales_order_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->sales_order_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'sales_order_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>

                            {{-- Opening Points --}}
                            <tr>
                                <td>Opening Points</td>
                                <td>Has the Distributor been informed to provide the opening points balance on the</td>
                                <td><input type="text" name="opening_points" class="form-control" readonly></td>
                                <td>
                                    <input type="text" name="opening_points_remarks" 
                                        value="{{ optional($siteReady)->opening_points_remarks }}" class="form-control">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ optional($siteReady)->opening_points_status ? 'checked' : '' }}
                                            onclick="statusToggleSiteReady('{{ route('admin.client.siteStatus', ['slot_booking_id' => $slot->id, 'field' => 'opening_points_status']) }}', this)">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5">Save Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')
