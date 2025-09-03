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
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

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
                            <div class="form-group me-1 mb-0">
                                <input type="search" class="form-control form-control-sm" name="keyword" id="keyword" value="{{ request()->input('keyword') }}" placeholder="Search something...">
                            </div>
                            {{-- search by client name  --}}
                            <select name="client_id[]" id="client_id" class="chosen-select" style="min-width: 200px;" multiple data-placeholder="Select options...">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" 
                                        {{ is_array(request('client_id')) && in_array($client->id, request('client_id')) ? 'selected' : '' }}>
                                        {{ ucwords($client->name) }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- search by date --}}
                            {{-- <select name="slot_date" id="slot_date" class="form-control form-control-sm select2 me-2" style="min-width: 200px;">
                                <option value="">-- All Dates --</option>
                                @foreach($slotDates as $date)
                                    <option value="{{ $date }}" {{ request('slot_date') == $date ? 'selected' : '' }}>
                                        {{ date('d-m-Y', strtotime($date)) }}
                                    </option>
                                @endforeach
                            </select> --}}
                            {{-- <label for="slot_date" class="form-label">Select Slot Date</label> --}}
                            <input type="text" class="form-control" name="slot_date" id="slot_date"
                                placeholder="Select slot date" autocomplete="off"
                                value="{{ request('slot_date')}}">

                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="tf-icons ri-filter-3-line"></i>
                            </button>

                            <a href="{{ url()->current() }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Clear filter">
                                <i class="tf-icons ri-close-line"></i>
                            </a>
                            <a href="{{ route('admin.client.exportDistList', request()->all()) }}" class="btn btn-success waves-effect btn-sm" 
                                data-toggle="tooltip" title="Export Data">
                                <i class="tf-icons ri-download-line"></i>
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
                        <th>Distributor Details</th>
                        <th>Slot Date</th>
                        <th>Site Ready</th>
                        <th>Training Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($distributor as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $d->user ? ucwords($d->user->name) : 'NA'}}</td>
                            <td>{{ ucwords($d->distributor_name) }}</td>
                             <td>
                                <ul>
                                    <li>Address: {{ ucwords($d->distributor_address) }}</li>
                                    <li>Contact: {{ $d->distributor_contact_no }}</li>
                                    <li>Email: {{ $d->distributor_email }}</li>
                                </ul>
                            </td>
                            <td>{{ date('d-m-Y',strtotime($d->slot_date)) }}</td>
                            <td>
                                <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                                    <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$d->id}}"
                                        {{ $d->site_ready ? 'checked' : '' }}
                                        onclick="statusSiteReadyToggle('{{route('admin.client.siteReady', $d->id)}}', this)">
                                    <label class="form-check-label" for="customSwitch{{$d->id}}"></label>
                                </div>                            
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-1 shadow-sm px-5" 
                                    data-bs-toggle="modal" data-bs-target="#remarksModal" data-id="{{ $d->id }}"
                                    data-remarks="{{ $d->remarks }}">Remarks
                                </button>
                            </td>
                            <td>
                                @if($d->site_ready)
                                    <div class="form-check form-switch" data-bs-toggle="tooltip">
                                        <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$d->id}}"
                                            {{ $d->site_ready ? 'checked' : '' }}
                                            onclick="statusToggle('{{route('admin.client.trainingDone', $d->id)}}', this)">
                                        <label class="form-check-label" for="customSwitch{{$d->id}}"></label>
                                    </div>
                                    
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-1 shadow-sm px-5" 
                                        data-bs-toggle="modal" data-bs-target="#remarksTrainingModal" data-id="{{ $d->id }}"
                                        data-remarks="{{ $d->training_remarks }}">Training Remarks
                                    </button>
                                @else
                                    {{-- keep blank --}}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No distributors found for your account.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $distributor->links() }}
        </div>
       
        <!-- Site ready Remarks Modal -->
        <div class="modal fade" id="remarksModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.client.saveRemarks') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="slot_id"> 
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

        <!-- Training Remarks Modal -->
        <div class="modal fade" id="remarksTrainingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.client.saveRemarksTraining') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="training_slot_id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add / Update Training Remarks</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <textarea name="training_remarks" id="training_remarks_text" class="form-control" rows="4"></textarea>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    
    document.addEventListener("DOMContentLoaded", function () {
        // get all allowed dates from backend
        let activeDates = @json($availableDates); 

        flatpickr("#slot_date", {
            dateFormat: "Y-m-d",
            enable: activeDates,
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                // if this date is in your activeDates array
                let dateStr = fp.formatDate(dayElem.dateObj, "Y-m-d");
                if (activeDates.includes(dateStr)) {
                    // add custom class
                    dayElem.classList.add("highlight-date");
                }
            }
        });
    });

    var $jq = jQuery.noConflict();
    $jq(document).ready(function() {
        $jq(".chosen-select").chosen({
            width: "100%",            
            no_results_text: "Oops, nothing found!", 
            allow_single_deselect: true
        });
    });


    //site ready remarks modal   
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

    //training remarks modal
    document.addEventListener("DOMContentLoaded", function () {
        var remarksTrainingModal = document.getElementById('remarksTrainingModal');
        remarksTrainingModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var trainingSlotId = button.getAttribute('data-id');
            var trainingRemarks = button.getAttribute('data-remarks');

            document.getElementById('training_slot_id').value = trainingSlotId;
            document.getElementById('training_remarks_text').value = trainingRemarks ? trainingRemarks : '';
        });
    });
</script>
@endsection