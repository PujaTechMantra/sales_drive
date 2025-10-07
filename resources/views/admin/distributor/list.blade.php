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
        content: "COMPLETE";
        color: green;
    }
    .form-check-input:not(:checked) + .form-check-label::after {
        content: "PENDING";
        color: red;
    }

    .slot-date-col {
        width: 130px; 
        min-width: 130px;
        text-align: center;
    }
</style>


<div class="container my-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center h-50">
            <h4 class="mb-0">Distributor List</h4>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.slot-booking.distributorList') }}">
                <div class="row mb-3">
                    
                    <div class="form-group d-flex justify-content-between align-items-center mb-0">
                        {{-- <div class="col-md-6"></div> --}}
                        <div style="width:200px;">
                            <input type="search" class="form-control form-control-sm" name="keyword" id="keyword" 
                                value="{{ request()->input('keyword') }}" placeholder="Search" style=" width:200px; height: 5px">
                        </div>
                        {{-- Left side (Slot Date Filter) --}}
                        
                        <div class="d-flex align-items-center ms-2">      
                     
                            {{-- search by client name  --}}
                            <select name="client_id[]" id="client_id" class="chosen-select" style="min-width: 200px;" multiple data-placeholder="Select clients...">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" 
                                        {{ is_array(request('client_id')) && in_array($client->id, request('client_id')) ? 'selected' : '' }}>
                                        {{ ucwords($client->name) }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- search by date --}}
                            <input type="text" class="form-control" name="slot_date" id="slot_date"
                                placeholder="Select slot date" autocomplete="off" style="height: 30px" 
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

                            <button type="button" onclick="refresh_data()" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Refresh Slot">
                                <i class="tf-icons ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Distributor Name</th>
                            <th>Distributor Details</th>
                            <th>Slot Date</th>
                            <th>Slot Time</th>
                            <th>Site Ready</th>
                            <th>Training Status</th>
                            <th>Complete status</th>
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
                                        <li>Distributor Code: {{$d->distributor_code}}</li>
                                        <li>Address: {{ ucwords($d->distributor_address) }}</li>
                                        <li>City: {{ ucwords($d->city) }}</li>
                                        <li>State: {{ ucwords($d->state) }}</li>
                                        <li>Zone: {{ ucwords($d->zone) }}</li>
                                        <li>Contact: {{ $d->distributor_contact_no }}</li>
                                        <li>Email: {{ $d->distributor_email }}</li>
                                        <li>PAN:{{ $d->pan_number}}</li>
                                        <li>GST:{{ $d->gst_number }}</li>
                                        <li>Distributor Contact person:{{ ucwords($d->distributor_contact_person ? $d->distributor_contact_person : 'NA')}}</li>
                                        <li>Distributor Contact person Phone:{{ $d->distributor_contact_person_phone ? $d->distributor_contact_person_phone : 'NA' }}</li>
                                        <li>SO Name:{{ ucwords($d->so_name ? $d->so_name : 'NA') }}</li>
                                        <li>SO Contact:{{ $d->so_contact_no ? $d->so_contact_no : 'NA'}}</li>
                                    </ul>
                                </td>
                                <!-- <td>{{ date('d-m-Y',strtotime($d->slot_date)) }}</td> -->
                                <td class="slot-date-col">
                                    <div class="mb-2">{{ date('d-m-Y',strtotime($d->slot_date_1st)) }}</div>

                                   @php
                                        $dates = [];

                                        $filteredReschedules = $d->reschedules->filter(function ($log) use ($d) {
                                            return isset($log->user_data['distributor_code']) &&
                                                $log->user_data['distributor_code'] === $d->distributor_code;
                                        });

                                        $count = 1;
                                        foreach($filteredReschedules as $reschedule){
                                            if(isset($reschedule->user_data['slot_date'])){
                                                $dates[] = ['label' => 'Reschedule '.$count++, 'date' => \Carbon\Carbon::parse($reschedule->user_data['slot_date'])->format('d-m-Y')];
                                            }
                                        }
                                    @endphp

                                    @foreach($dates as $date)
                                        <div class="mb-2"><span class="text-info">{{ $date['label'] }}:</span>
                                        {{ $date['date'] }}</div>
                                    @endforeach
                                </td>


                                <td><div class="slot-date-col">{{ date('h:i A',strtotime($d->slot_start_time))}} - {{date('h:i A',strtotime($d->slot_end_time))}}</div></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 ">
                                        {{-- <button type="button">Site readiness form</button> --}}
                                        <a href="{{ route('admin.client.siteReadinessForm', $d->id)}}" target="_blank" 
                                            class="btn btn-outline-warning btn-sm">Site Readiness Form
                                        </a>
                                        <div class="d-flex form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                                            <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$d->id}}"
                                                {{ $d->site_ready ? 'checked' : '' }}
                                                onclick="statusSiteReadyToggle('{{route('admin.client.siteReady', $d->id)}}', this)">
                                            <label class="form-check-label" for="customSwitch{{$d->id}}"></label>
                                        </div>                            
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-1 shadow-sm" 
                                            data-bs-toggle="modal" data-bs-target="#remarksModal" data-id="{{ $d->id }}"
                                            data-remarks="{{ $d->remarks }}">Remarks
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 ">

                                    @if($d->site_ready)
                                        <div class="d-flex form-check form-switch" data-bs-toggle="tooltip">
                                            <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$d->id}}"
                                                {{ $d->training_done ? 'checked' : '' }}
                                                onclick="statusToggle('{{route('admin.client.trainingDone', $d->id)}}', this)">
                                            <label class="form-check-label" for="customSwitch{{$d->id}}"></label>
                                        </div>
                                        
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-1 shadow-sm px-5" 
                                            data-bs-toggle="modal" data-bs-target="#remarksTrainingModal" training-data-id="{{ $d->id }}"
                                            data-training-remarks="{{ $d->training_remarks }}">Remarks
                                        </button>
                                    @else
                                        {{-- keep blank --}}
                                    @endif
                                    </div>

                                </td>
                                
                                <td>
                                    <div class="d-flex gap-2" data-bs-toggle="tooltip">

                                    @if($d->site_ready == 1 && $d->training_done == 1)
                                        {{-- Both complete → show SUCCESS --}}
                                        <span class="badge bg-success rounded-pill px-3 py-2">SUCCESS</span>

                                    @elseif($d->complete_status == 'rescheduled')
                                        {{-- After client reschedules → show RESCHEDULED --}}
                                        <span class="badge bg-info rounded-pill px-3 py-2">RESCHEDULED</span>
                                    @elseif($d->complete_status == 'failed')
                                        <span class="badge bg-danger rounded-pill px-3 py-2">FAILED</span>
                                                    
                                        <span class="badge bg-warning rounded-pill px-3 py-2">WAITING FOR RESCHEDULE</span>

                                    @elseif($d->site_ready == 0 && $d->training_done == 0)
                                        {{-- Both are 0 → show dropdown --}}
                                          
                                            <div class="mt-2 status-badge">
                                                <!-- @if($d->complete_status == 'waiting for reschedule')
                                                    <span class="badge bg-danger rounded-pill px-3 py-2">WAITING FOR RESCHEDULE</span>
                                                @else -->
                                                    <span class="badge bg-warning rounded-pill px-3 py-2">PENDING</span>
                                                <!-- @endif -->
                                            </div>
                                    </div>

                                    @else
                                        {{-- One is 1 and other is 0 → nothing shown --}}
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
            </div>
            {{ $distributor->links() }}
        </div>
       
        <!-- Site ready Remarks Modal -->
        <div class="modal fade" id="remarksModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.client.savesiteReadyRemarks') }}" method="POST">
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
                <form action="{{ route('admin.client.savetrainingRemarks') }}" method="POST">
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
<link rel="stylesheet" href="{{ asset('build/assets/flatpickr.min.css') }}">
<script src="{{ asset('build/assets/flatpickr.js')}}"></script>
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
            var trainingSlotId = button.getAttribute('training-data-id');
            var trainingRemarks = button.getAttribute('data-training-remarks');

            document.getElementById('training_slot_id').value = trainingSlotId;
            document.getElementById('training_remarks_text').value = trainingRemarks ? trainingRemarks : '';
        });
    });

    //complete status   
    $jq(document).on("change", ".status-dropdown", function () {
        let status = $(this).val();
        let id = $(this).data("id");
        let badgeDiv = $(this).closest("td").find(".status-badge");

        // Update badge instantly
        if (status === "pending") {
            badgeDiv.html('<span class="badge bg-warning rounded-pill px-3 py-2">PENDING</span>');
        }

        // Save to DB using route()
        $.ajax({
            url: "{{ route('admin.client.completeStatus', ':id') }}".replace(':id', id),
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                status: status
            },
            success: function (data){
                if (data.status != 200) {
                    toastFire('error', data.message);
                } else {
                    toastFire('success', data.message);
                    location.reload();
                }
            }
        });
    });

    function refresh_data() {
    // console.log('Refresh button clicked');
        Swal.fire({
            icon: 'warning',
            title: "Are you sure you want to refresh incomplete slots?",
            // text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                        
                // if (!confirm('Are you sure you want to refresh incomplete slots?')) return;

                $jq.ajax({
                    url: "{{ route('admin.slot-booking.refresh') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        if(response.status === 200){
                            toastFire('success', response.message + " (Total updated: " + response.updated_count + ")");
                            setTimeout(() => {
                                location.reload();
                            }, 5000);
                        } else {
                            toastFire('error','Something went wrong.');
                        }
                    },
                    error: function(err){
                        console.error(err);
                        // alert('Error refreshing data.');
                    }
                });
            }
         });
    }



</script>
@endsection