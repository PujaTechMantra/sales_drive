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
                    <th style="width:20%">Section</th>
                    <th style="width:25%">Field</th>
                    <th style="width:35%">Input</th>
                    <th style="width:20%">checklist</th>
                </tr>
            </thead>
            <tbody>
                {{-- Distributor Information --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>Distributor Information</strong></td>
                </tr>

                <tr>
                    <td rowspan="12" class="align-middle">Distributor Creation</td>
                    <td>Distributor Code (SAP)</td>
                    <td><input type="text" name="distributor_code" class="form-control"></td>
                </tr>
                <tr>
                    <td>Distributor Name</td>
                    <td><input type="text" name="distributor_name" class="form-control" value="{{ ucwords($slot->distributor_name) }}" readonly></td>
                </tr>
                <tr>
                    <td>Full Address</td>
                    <td><textarea name="full_address" class="form-control" readonly> {{ ucwords($slot->distributor_address)}}</textarea></td>
                </tr>
                <tr>
                    <td>Distributor Office Phone no</td>
                    <td><input type="text" name="distributor_office_phone_no" class="form-control" value="{{ $slot->distributor_contact_no }}" readonly></td>
                </tr>
                <tr>
                    <td>City</td>
                    <td><input type="text" name="city" class="form-control"></td>
                </tr>
                <tr>
                    <td>State</td>
                    <td><input type="text" name="state" class="form-control"></td>
                </tr>
                <tr>
                    <td>Zone</td>
                    <td><input type="text" name="zone" class="form-control"></td>
                </tr>
                <tr>
                    <td>Distributor Contact Person</td>
                    <td><input type="text" name="distributor_contact_person" class="form-control"></td>
                </tr>
                <tr>
                    <td>Distributor Contact Person Phone no</td>
                    <td><input type="text" name="distributor_contact_person_phone_no" class="form-control"></td>
                </tr>
                <tr>
                    <td>GST</td>
                    <td><input type="text" name="gst" class="form-control" value="{{ $slot->gst_number}}"></td>
                </tr>
                <tr>
                    <td>PAN</td>
                    <td><input type="text" name="pan" class="form-control" value="{{ $slot->pan_number }}"></td>
                </tr>
                <tr>
                    <td>SO Name</td>
                    <td><input type="text" name="so_name" class="form-control"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>SO Contact Number</td>
                    <td><input type="text" name="so_contact_number" class="form-control"></td>
                </tr>
                {{-- Master Data Requirement --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>Master Data Requirement</strong></td>
                </tr>
                <tr>
                    <td rowspan="6" class="align-middle">Brand Mapping<br>Beat Creation</td>
                    <td>Mention all Brands</td>
                    <td><input type="text" name="mention_all_brands" class="form-control"></td>
                </tr>
                <tr>
                    <td>Beat Name</td>
                    <td><input type="text" name="beat_name" class="form-control"></td>
                </tr>
                <tr>
                    <td>Beat ID</td>
                    <td><input type="text" name="beat_id" class="form-control"></td>
                </tr>
                <tr>
                    <td>Beat Type - Normal/Split</td>
                    <td>
                        <select name="beat_type" class="form-select">
                            <option value="Normal">Normal</option>
                            <option value="Split">Split</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Region Code & Region Name- In CSP</td>
                    <td><input type="text" name="region_code_csp" class="form-control"></td>
                </tr>
                <tr>
                    <td>Distributor Codes</td>
                    <td><input type="text" name="distributor_codes_master" class="form-control"></td>
                </tr>

                {{-- Employee List Creation --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>Employee List Creation</strong></td>
                </tr>
                <tr>
                    <td rowspan="8" class="align-middle">Employee List Creation</td>
                    <td>Employee ID (Label of SAP)</td>
                    <td><input type="text" name="employee_id" class="form-control"></td>
                </tr>
                <tr>
                    <td>Employee Label (ID of SAP)</td>
                    <td><input type="text" name="employee_label" class="form-control"></td>
                </tr>
                <tr>
                    <td>Employee Name</td>
                    <td><input type="text" name="employee_name" class="form-control"></td>
                </tr>
                <tr>
                    <td>Designation Code- CSP</td>
                    <td><input type="text" name="designation_code_csp" class="form-control"></td>
                </tr>
                <tr>
                    <td>RM Employee ID</td>
                    <td><input type="text" name="rm_employee_id" class="form-control"></td>
                </tr>
                <tr>
                    <td>RM Designation Code</td>
                    <td><input type="text" name="rm_designation_code" class="form-control"></td>
                </tr>
                <tr>
                    <td>State Code</td>
                    <td><input type="text" name="employee_state_code" class="form-control"></td>
                </tr>
                <tr>
                    <td>Distributor Codes</td>
                    <td><input type="text" name="distributor_codes_employee" class="form-control"></td>
                </tr>

                {{-- Employee-Distributor Mapping --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>Employee-Distributor Mapping</strong></td>
                </tr>
                <tr>
                    <td rowspan="4" class="align-middle">Employee-Distributor Mapping<br>DSR-Distributor Mapping<br>Beat-Employee Mapping<br>Supplier- Distributor Mapping</td>
                    <td>DSR-Distributor Mapping</td>
                    {{-- <td>
                        <input type="checkbox" name="dsr_distributor_mapping" class="form-check-input">
                    </td> --}}
                </tr>
                <tr>
                    <td>Beat-Employee Mapping</td>
                    {{-- <td>
                        <input type="checkbox" name="beat_employee_mapping" class="form-check-input">
                    </td> --}}
                </tr>
                <tr>
                    <td>Supplier- Distributor Mapping</td>
                    {{-- <td>
                        <input type="checkbox" name="supplier_distributor_mapping" class="form-check-input">
                    </td> --}}
                </tr>
                <tr>
                    <td>Outlet Automated</td>
                    <td><input type="text" name="outlet_automated" class="form-control" placeholder="Sync in CSP"></td>
                </tr>

                {{-- Outlet Manual Creation --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>Outlet Manual Creation</strong></td>
                </tr>
                <tr>
                    <td rowspan="2" class="align-middle">Outlet Manual Creation</td>
                    <td>Outlet Lead Creation</td>
                    <td><input type="text" name="outlet_lead_creation" class="form-control"></td>
                </tr>
                <tr>
                    <td>Outlet Lead Approval</td>
                    <td><input type="text" name="outlet_lead_approval" class="form-control"></td>
                </tr>

                {{-- Regional Price --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>Regional Price</strong></td>
                </tr>
                <tr>
                    <td>Regional Price</td>
                    <td></td>
                    {{-- <td><input type="checkbox" name="regional_price" class="form-check-input"></td> --}}
                </tr>

                {{-- Opening Stock --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>Opening Stock</strong></td>
                </tr>
                <tr>
                    <td>Opening Stock</td>
                    <td>Has the Distributor been informed to provide the opening stocks before the day</td>
                    {{-- <td><input type="checkbox" name="opening_stock" class="form-check-input"></td> --}}
                </tr>

                {{-- GRN/Invoice --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>GRN/Invoice</strong></td>
                </tr>
                <tr>
                    <td>GRN/Invoice</td>
                    <td>Is GRN Displaying correctly in system?</td>
                    {{-- <td><input type="checkbox" name="grn_invoice_displaying_correctly" class="form-check-input"></td> --}}
                </tr>

                {{-- Sales Order --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>Sales Order</strong></td>
                </tr>
                <tr>
                    <td>Sales Order</td>
                    <td>Is Sales Order Displaying correctly from</td>
                    {{-- <td><input type="checkbox" name="sales_order_displaying_correctly" class="form-check-input"></td> --}}
                </tr>

                {{-- Opening Points --}}
                <tr class="table-secondary">
                    <td colspan="3"><strong>Opening Points</strong></td>
                </tr>
                <tr>
                    <td>Opening Points</td>
                    <td>Has the Distributor been informed to provide the opening points balance on the</td>
                    {{-- <td><input type="checkbox" name="opening_points_balance" class="form-check-input"></td> --}}
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
