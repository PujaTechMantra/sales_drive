@extends('layouts/contentNavbarLayoutClient')

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
    .slot-date-col{
        width: 130px; /* adjust as needed */
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
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Distributor List of {{ ucwords(Auth::guard('client')->user()->name) }}</h4>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('client.slot-booking.distributorList') }}">
                <div class="row mb-3">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="col-md-6"></div>
                        {{-- Left side (Slot Date Filter) --}}
                        <div class="form-group d-flex align-items-center mb-0">
                            <input type="text" class="form-control" name="slot_date" id="slot_date"
                                placeholder="Select slot date" autocomplete="off" style="height: 30px"
                                value="{{ request('slot_date')}}">

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
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>#</th>
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
                                    <div class="d-flex gap-2">
                                        @if($d->site_ready == 1)
                                            <span class="badge bg-success rounded-pill px-3 py-2">COMPLETE</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill px-3 py-2">PENDING</span>
                                        @endif

                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle viewRemarksBtn"
                                            data-bs-toggle="modal" data-bs-target="#remarksModal"
                                            data-remarks="{{ $d->remarks}}" title="Remarks"><i class="ri-eye-line ri-15px"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">

                                    @if($d->user && $d->user->training_status == 0)
                                        <span class="badge bg-danger rounded-pill px-3 py-2">PENDING</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle trainingViewRemarksBtn"
                                            data-bs-toggle="modal" data-bs-target="#trainingRemarksModal"
                                            data-remarks="{{ $d->training_remarks}}" title="Remarks"><i class="ri-eye-line ri-15px"></i>
                                        </button>
                                    @else
                                        @if($d->site_ready)
                                            <div class="d-flex form-check form-switch" data-bs-toggle="tooltip">
                                                <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$d->id}}"
                                                    {{ $d->training_done ? 'checked' : '' }}
                                                    onclick="statusToggle('{{route('client.trainingDone', $d->id)}}', this)">
                                                <label class="form-check-label" for="customSwitch{{$d->id}}"></label>
                                            </div>
                                            
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-1 shadow-sm px-5" 
                                                data-bs-toggle="modal" data-bs-target="#remarksTrainingModal" data-id="{{ $d->id }}"
                                                data-remarks="{{ $d->training_remarks }}">Remarks
                                            </button>
                                        @else
                                            <h8>Waiting for site ready...</h8>                                        
                                        @endif
                                    @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                    @if($d->site_ready == 1 && $d->training_done == 1)
                                  
                                        <span class="badge bg-success rounded-pill px-3 py-2">SUCCESS</span>

                                    @elseif($d->complete_status == 'rescheduled')
                                        <span class="badge bg-info rounded-pill px-3 py-2">RESCHEDULED</span>

                                    @elseif($d->complete_status == 'failed')
                                        <span class="badge bg-danger rounded-pill px-3 py-2">FAILED</span>
                                        <span class="badge bg-warning rounded-pill px-3 py-2">Waiting for reschedule</span>

                                        <a href="{{ route('client.slot.rescheduleForm', $d->id) }}" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm px-4 rescheduleBtn">
                                            Reschedule
                                        </a>

                                    @elseif(($d->site_ready == 0 && $d->training_done == 0) || $d->complete_status == 'pending')
                                        <span class="badge bg-warning rounded-pill px-3 py-2">Wait for status</span>

                                        <a href="{{ route('client.slot.rescheduleForm', $d->id) }}" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm px-4 rescheduleBtn">
                                            Reschedule
                                        </a>
                         
                                    @endif
                                </div>
                                    
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

        {{-- site ready remarks modal view --}}
        <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="remarksModalLabel">Remarks</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="remarksText" class="text-muted"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

         <!-- Training Remarks Modal -->
        <div class="modal fade" id="remarksTrainingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('client.savetrainingRemarks') }}" method="POST">
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

    //site ready view remarks modal
    document.addEventListener('DOMContentLoaded', function() {
        const remarksModal = document.getElementById('remarksModal');
        const remarksText = document.getElementById('remarksText');

        document.querySelectorAll('.viewRemarksBtn').forEach(button => {
            button.addEventListener('click', function() {
                let remarks = this.getAttribute('data-remarks');
                remarksText.textContent = remarks ? remarks : 'No Reamrks available.';
            })
        })
    })
  

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