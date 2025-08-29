@extends('layouts/contentNavbarLayout')

@section('title', 'Distributor list')

@section('content')
<style>
    /* Remove default background color of switch */
    .form-check-input:checked {
        background-color: #198754 !important; /* optional green color */
        border-color: #198754 !important;
    }
    .form-check-input:not(:checked) {
        background-color: #dc3545 !important; /* optional red color */
        border-color: #dc3545 !important;
    }

    /* Show YES / NO text */
    .form-check-label::after {
        margin-left: 8px;
        font-weight: bold;
    }
    .form-check-input:checked + .form-check-label::after {
        content: "YES";
        color: green;
    }
    .form-check-input:not(:checked) + .form-check-label::after {
        content: "NO";
        color: red;
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Distributor List</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.slot-booking.distributorList') }}">
                <div class="row mb-3">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="col-md-6"></div>
                        {{-- Left side (Slot Date Filter) --}}
                        <div class="form-group d-flex align-items-center mb-0">
                            {{-- <label for="slot_date" class="me-2">Slot Date</label> --}}
                            <div class="form-group me-1 mb-0">
                                <input type="search" class="form-control form-control-sm" name="keyword" id="keyword" value="{{ request()->input('keyword') }}" placeholder="Search something...">
                            </div>
                            <select name="slot_date" id="slot_date" class="form-control form-control-sm select2 me-2" style="min-width: 200px;">
                                <option value="">-- All Dates --</option>
                                @foreach($slotDates as $date)
                                    <option value="{{ $date }}" {{ request('slot_date') == $date ? 'selected' : '' }}>
                                        {{ date('d-m-Y', strtotime($date)) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary me-1">
                                <i class="tf-icons ri-filter-3-line"></i>
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Clear filter">
                                <i class="tf-icons ri-close-line"></i>
                            </a>
                        </div>

                    </div>

                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client Name</th>
                        <th>Distributor Name</th>
                        <th>Distributor Address</th>
                        <th>Distributor Contact No</th>
                        <th>Distributor Email</th>
                        <th>Slot Date</th>
                        <th>Site Ready</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($distributor as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $d->user ? ucwords($d->user->name) : 'NA'}}</td>
                            <td>{{ ucwords($d->distributor_name) }}</td>
                            <td>{{ ucwords($d->distributor_address) }}</td>
                            <td>{{ $d->distributor_contact_no }}</td>
                            <td>{{ $d->distributor_email }}</td>
                            <td>{{ date('d-m-Y',strtotime($d->slot_date)) }}</td>
                            <td>
                                <div class="form-check form-switch" data-bs-toggle="tooltip">
                                    <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$d->id}}"
                                        {{ $d->site_ready ? 'checked' : '' }}
                                        onclick="statusToggle('{{route('admin.client.siteReady', $d->id)}}', this)">
                                    <label class="form-check-label" for="customSwitch{{$d->id}}"></label>
                                </div>
                                {{-- Hidden input to store the URL --}}
                                {{-- <input type="hidden" id="siteReadyUrl{{$d->id}}" value="{{ route('admin.client.siteReady', $d->id) }}"> --}}

                                <button type="button" class="badge rounded-pill bg-secondary" 
                                        data-bs-toggle="modal" data-bs-target="#remarksModal" data-id="{{ $d->id }}"
                                        data-remarks="{{ $d->remarks }}">Remarks</button>
                                {{-- <input type="hidden" id="remarksUrl{{$d->id}}" value="{{ route('admin.client.saveRemarks')}}"> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No distributors found for your account.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- <div class="modal fade" id="remarksModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                <form id="remarksForm" method="POST">
                    @csrf
                    <div class="modal-header">
                    <h5 class="modal-title">Add Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <textarea name="remarks" class="form-control" rows="4" placeholder="Enter remarks..."></textarea>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
                </div>
            </div>
        </div> --}}
        <!-- Remarks Modal -->
        <div class="modal fade" id="remarksModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.client.saveRemarks') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="slot_id"> {{-- slot_booking id will go here --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add / Update Remarks</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="remarks" id="remarks_text" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
        </div>


    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#slot_date').select2({
            placeholder: "Select a slot date",
            allowClear: true
        });
    });

    // function openRemarksModal(id) {
    //     let url = document.getElementById("remarksUrl" + id).value;
    //     document.getElementById("remarksForm").action = url; // set form action inside modal
    // }
    document.addEventListener("DOMContentLoaded", function () {
        var remarksModal = document.getElementById('remarksModal');
        remarksModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var slotId = button.getAttribute('data-id');
            var remarks = button.getAttribute('data-remarks');

            document.getElementById('slot_id').value = slotId;
            document.getElementById('remarks_text').value = remarks ? remarks : '';
        });
    });
</script>
@endsection