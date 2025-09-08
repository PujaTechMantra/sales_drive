@extends('layouts/contentNavbarLayout')

@section('title', 'SiteReadiness Form')

@section('content')
<div class="container my-4">
         <a href="{{ route('admin.slot-booking.distributorList') }}" class="btn btn-sm btn-danger">
        <i class="tf-icons ri-arrow-left-line"></i> Back
    </a>
    <h4 class="fw-bold text-center mb-4">
        Pre-Implementation checklist (Site Readiness) - Preparation for installation by Tech Mantra
    </h4>

    <form method="POST" action="#">
        @csrf
        <input type="hidden" name="slot_booking_id" value="{{ $slot->id }}">
        <table class="table table-bordered table-striped align-middle">
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
                <tr class="table-secondary">
                    <td colspan="5"><strong>Distributor Information</strong></td>
                </tr>

                <tr>
                    <td rowspan="12" class="align-middle">Distributor Creation</td>
                    <td>Distributor Code (SAP)</td>
                    <td><input type="text" name="distributor_code" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->distributor_code_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'distributor_code_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->distributor_code_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'distributor_code_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Distributor Name</td>
                    <td><input type="text" name="distributor_name" class="form-control" value="{{ ucwords($slot->distributor_name) }}" readonly></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->distributor_name_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'distributor_name_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->distributor_name_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'distributor_name_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Full Address</td>
                    <td><textarea name="full_address" class="form-control" readonly> {{ ucwords($slot->distributor_address)}}</textarea></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->full_address_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'full_address_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->full_address_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'full_address_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Distributor Phone no</td>
                    <td><input type="text" name="office_phone_no_status" class="form-control" value="{{ $slot->distributor_contact_no }}" readonly></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->office_phone_no_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'office_phone_no_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->office_phone_no_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'office_phone_no_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>

                 <tr>
                    <td>Distributor Email ID</td>
                    <td><input type="text" name="distributor_email" class="form-control" value="{{ $slot->distributor_email }}" readonly></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->distributor_email_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'distributor_email_status']) }}', this)"> --}}
                                @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->distributor_email_status ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'distributor_email_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>City</td>
                    <td><input type="text" name="city" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->city_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'city_status']) }}', this)"> --}}
                                @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->city_status  ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'city_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>State</td>
                    <td><input type="text" name="state" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->state_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'state_status']) }}', this)"> --}}
                                 @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->state_status ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'state_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Zone</td>
                    <td><input type="text" name="zone" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->zone_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'zone_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->zone_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'zone_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                  <tr>
                    <td>GST</td>
                    <td><input type="text" name="gst_number" class="form-control" value="{{ $slot->gst_number}}"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->gst_number_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'gst_number_status']) }}', this)"> --}}
                                @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->gst_number_status ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'gst_number_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>PAN</td>
                    <td><input type="text" name="pan_number" class="form-control" value="{{ $slot->pan_number }}"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->pan_number_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'pan_number_status']) }}', this)"> --}}
                                @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->pan_number_status ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'pan_number_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Distributor Contact Person (Optional)</td>
                    <td><input type="text" name="contact_person" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->contact_person_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'contact_person_status']) }}', this)"> --}}
                                @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->contact_person_status ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'contact_person_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Distributor Contact Person Phone no (Optional)</td>
                    <td><input type="text" name="contact_person_phone_no_status" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->contact_person_phone_no_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'contact_person_phone_no_status']) }}', this)"> --}}
                                @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->contact_person_phone_no_status ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'contact_person_phone_no_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>SO Name (Optional)</td>
                    <td><input type="text" name="so_name" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->so_name_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id,
                                 'field' => 'so_name_status']) }}', this)"> --}}
                                @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->so_name_status ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'so_name_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>SO Contact Number (Optional)</td>
                    <td><input type="text" name="so_contact_number" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->so_contact_number_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'so_contact_number_status']) }}', this)"> --}}
                                @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->so_contact_number_status ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'so_contact_number_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>
                {{-- Master Data Requirement --}}
                <tr class="table-secondary">
                    <td colspan="5"><strong>Master Data Requirement</strong></td>
                </tr>
                <tr>
                    <td rowspan="6" class="align-middle">Brand Mapping<br>Beat Creation</td>
                    <td>Mention all Brands</td>
                    <td><input type="text" name="brands" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->brands_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'brands_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->brands_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'brands_status']) }}', this)">
                            @endif   
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Beat Name</td>
                    <td><input type="text" name="beat_name" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->beat_name_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'beat_name_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->beat_name_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'beat_name_status']) }}', this)">
                            @endif 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Beat ID</td>
                    <td><input type="text" name="beat_id" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->beat_id_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'beat_id_status']) }}', this)"> --}}
                                @if($slot->siteReadinessForm)
                                    <input class="form-check-input" type="checkbox"
                                        {{ $slot->siteReadinessForm->beat_id_status ? 'checked' : '' }}
                                        onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'beat_id_status']) }}', this)">
                                @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Beat Type - Normal/Split</td>
                    {{-- <td>
                        <select name="beat_type" class="form-select">
                            <option value="Normal">Normal</option>
                            <option value="Split">Split</option>
                        </select>
                    </td> --}}
                    <td><input type="text" name="beat_type" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->beat_type_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'beat_type_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->beat_type_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'beat_type_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Region Code </td>
                    <td><input type="text" name="region_code" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->region_code_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'region_code_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->region_code_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'region_code_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Region Name- In CSP</td>
                    <td><input type="text" name="region_name" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->region_name_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'region_name_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->region_name_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'region_name_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Distributor Codes</td>
                    <td><input type="text" name="beat_distributor_codes" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->beat_distributor_codes_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'beat_distributor_codes_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->beat_distributor_codes_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'beat_distributor_codes_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Employee List Creation --}}
                <tr class="table-secondary">
                    <td colspan="5"><strong>Employee List Creation</strong></td>
                </tr>
                <tr>
                    <td rowspan="8" class="align-middle">Employee List Creation</td>
                    <td>Employee ID</td>
                    <td><input type="text" name="employee_id" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->employee_id_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'employee_id_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->employee_id_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'employee_id_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Employee Label</td>
                    <td><input type="text" name="employee_label" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->employee_label_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'employee_label_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->employee_label_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'employee_label_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Employee Name</td>
                    <td><input type="text" name="employee_name" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->employee_name_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'employee_name_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->employee_name_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'employee_name_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Designation Code- CSP</td>
                    <td><input type="text" name="designation_code" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->designation_code_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id,
                                 'field' => 'designation_code_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->designation_code_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'designation_code_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>RM Employee ID</td>
                    <td><input type="text" name="rm_employee_id" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->rm_employee_id_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'rm_employee_id_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->rm_employee_id_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'rm_employee_id_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>RM Designation Code</td>
                    <td><input type="text" name="rm_designation_code" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->rm_designation_code_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'rm_designation_code_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->rm_designation_code_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'rm_designation_code_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>State Code</td>
                    <td><input type="text" name="state_code" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->state_code_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'state_code_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->state_code_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'state_code_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Distributor Codes</td>
                    <td><input type="text" name="employee_distributor_codes" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->employee_distributor_codes_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'employee_distributor_codes_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->employee_distributor_codes_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'employee_distributor_codes_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Employee-Distributor Mapping --}}
                <tr class="table-secondary">
                    <td colspan="5"><strong>Employee-Distributor Mapping</strong></td>
                </tr>
                <tr>
                    <td rowspan="4" class="align-middle">Employee-Distributor Mapping<br>DSR-Distributor Mapping
                        <br>Beat-Employee Mapping<br>Supplier- Distributor Mapping<br>Outlet Automated</td>
                    <td>Employee-Distributor Mapping</td>
                    <td><input type="text" name="employee_distributor_mapping" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->employee_distributor_mapping_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'employee_distributor_mapping_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->employee_distributor_mapping_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'employee_distributor_mapping_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                 <tr>
                    <td>DSR-Distributor Mapping</td>
                    <td><input type="text" name="dsr_distributor_mapping" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->dsr_distributor_mapping_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id,
                                 'field' => 'dsr_distributor_mapping_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->dsr_distributor_mapping_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'dsr_distributor_mapping_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Beat-Employee Mapping</td>
                    <td><input type="text" name="beat_employee_mapping" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->beat_employee_mapping_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id,
                                 'field' => 'beat_employee_mapping_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->beat_employee_mapping_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'beat_employee_mapping_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
               
                <tr>
                    <td>Supplier- Distributor Mapping</td>
                    <td><input type="text" name="supplier_distributor_mapping" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->supplier_distributor_mapping_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'supplier_distributor_mapping_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->supplier_distributor_mapping_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'supplier_distributor_mapping_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                   
                </tr>
                <tr>
                    <td></td>
                    <td>Sync in CSP</td>
                    <td><input type="text" name="outlet_sync_csp" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->outlet_sync_csp_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'outlet_sync_csp_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->outlet_sync_csp_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'outlet_sync_csp_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Outlet Manual Creation --}}
                <tr class="table-secondary">
                    <td colspan="5"><strong>Outlet Manual Creation</strong></td>
                </tr>
                <tr>
                    <td rowspan="2" class="align-middle">Outlet Manual Creation (optional)</td>
                    <td>Outlet Lead Creation</td>
                    <td><input type="text" name="outlet_lead_creation" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->outlet_lead_approval_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'outlet_lead_creation_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->outlet_lead_approval_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'outlet_lead_approval_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Outlet Lead Approval</td>
                    <td><input type="text" name="outlet_lead_approval" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->outlet_lead_approval_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'outlet_lead_approval_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->outlet_lead_approval_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'outlet_lead_approval_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Regional Price --}}
                <tr class="table-secondary">
                    <td colspan="5"><strong>Regional Price</strong></td>
                </tr>
                <tr>
                    <td>Price: Regional/National</td>
                    <td></td>
                    <td><input type="text" name="regional_price" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->regional_price_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'regional_price_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->regional_price_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'regional_price_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Opening Stock --}}
                <tr class="table-secondary">
                    <td colspan="5"><strong>Opening Stock</strong></td>
                </tr>
                <tr>
                    <td>Opening Stock</td>
                    <td>Has the Distributor been informed to provide the opening stocks before the day</td>
                    <td><input type="text" name="opening_stock" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->opening_stock_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'opening_stock_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->opening_stock_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'opening_stock_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- GRN/Invoice --}}
                <tr class="table-secondary">
                    <td colspan="5"><strong>GRN/Invoice</strong></td>
                </tr>
                <tr>
                    <td>GRN/Invoice</td>
                    <td>Is GRN Displaying correctly in system?</td>
                    <td><input type="text" name="grn_invoice" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            {{-- <input class="form-check-input" type="checkbox"
                                {{ $siteReady->grn_invoice_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 
                                'field' => 'grn_invoice_status']) }}', this)"> --}}
                            @if($slot->siteReadinessForm)
                                <input class="form-check-input" type="checkbox"
                                    {{ $slot->siteReadinessForm->grn_invoice_status ? 'checked' : '' }}
                                    onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $slot->siteReadinessForm->id, 'field' => 'grn_invoice_status']) }}', this)">
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Sales Order --}}
                <tr class="table-secondary">
                    <td colspan="5"><strong>Sales Order</strong></td>
                </tr>
                <tr>
                    <td>Sales Order</td>
                    <td>Is Sales Order Displaying correctly from</td>
                    <td><input type="text" name="sales_order" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                {{ $siteReady->sales_order_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'sales_order_status']) }}', this)">
                        </div>
                    </td>
                </tr>

                {{-- Opening Points --}}
                <tr class="table-secondary">
                    <td colspan="5"><strong>Opening Points</strong></td>
                </tr>
                <tr>
                    <td>Opening Points</td>
                    <td>Has the Distributor been informed to provide the opening points balance on the</td>
                    <td><input type="text" name="opening_points" class="form-control"></td>
                    <td></td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                {{ $siteReady->opening_points_status ? 'checked' : '' }}
                                onclick="statusToggle('{{ route('admin.client.siteStatus', ['id' => $siteReady->id, 'field' => 'opening_points_status']) }}', this)">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary px-5">Save Checklist</button>
        </div>
    </form>
</div>

@endsection
@section('scripts')
