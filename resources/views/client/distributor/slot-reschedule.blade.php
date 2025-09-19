@extends('layouts/contentNavbarLayoutClient')

@section('title', 'Slot reschedule')

@section('content')

<div class="container">
    <h4 class="mb-4">Reschedule Slot for {{ ucwords($booking->distributor_name) }}</h4>
    <a href="{{ route('client.slot-booking.distributorList') }}" class="btn btn-sm btn-danger">
        <i class="menu-icon tf-icons ri-arrow-left-line"></i> Back
    </a>

    <div class="card-body p-4">
        <form action="{{ route('client.slot.saveReschedule') }}" method="POST">
            @csrf
            <!-- Hidden booking id -->
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="slot_date_reschedule" class="form-label fw-semibold">Slot Date</label> <span class="text-danger">*</span>
                    <input type="text" class="form-control" name="slot_date" id="slot_date_reschedule"
                        placeholder="Select slot date" autocomplete="off"
                        value="{{ $booking->slot_date }}">
                    <small id="slot_msg_reschedule" class="text-danger"></small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Select Time Slot</label> <span class="text-danger">*</span>
                    <select name="slot_id" id="slot_id_reschedule" class="form-control">
                        <option value="{{ $booking->slot_id }}">
                            {{ date('h:i A', strtotime($booking->slot_start_time)) }} to {{ date('h:i A', strtotime($booking->slot_end_time)) }}
                        </option>
                    </select>
                    <input type="hidden" name="slot_start_time" id="slot_start_time_reschedule" value="{{ $booking->slot_start_time }}">
                    <input type="hidden" name="slot_end_time" id="slot_end_time_reschedule" value="{{ $booking->slot_end_time }}">
                </div>
            </div>

            <!-- Distributor Info -->
            <div class="distributor-section bg-light-subtle border rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-primary">Distributor Information</h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Distributor Code (SAP)</label>
                        <input type="text" class="form-control" value="{{ $booking->distributor_code }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="{{ $booking->distributor_name }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Full Address</label>
                        <textarea class="form-control" rows="2" readonly>{{ $booking->distributor_address }}</textarea>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" value="{{ $booking->distributor_contact_no }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $booking->distributor_email }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">PAN</label>
                        <input type="text" class="form-control" value="{{ $booking->pan_number }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">GST</label>
                        <input type="text" class="form-control" value="{{ $booking->gst_number }}" readonly>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" value="{{ $booking->city }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <input type="text" class="form-control" value="{{ $booking->state }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Zone</label>
                        <input type="text" class="form-control" value="{{ $booking->zone }}" readonly>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Contact Person (optional)</label>
                        <input type="text" class="form-control" value="{{ $booking->distributor_contact_person }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Person Phone (optional)</label>
                        <input type="text" class="form-control" value="{{ $booking->distributor_contact_person_phone }}" readonly>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">SO Name (optional)</label>
                        <input type="text" class="form-control" value="{{ $booking->so_name }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SO Contact No (optional)</label>
                        <input type="text" class="form-control" value="{{ $booking->so_contact_no }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">Update Slot</button>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')

<link rel="stylesheet" href="{{ asset('build/assets/flatpickr.min.css') }}">
<script src="{{ asset('build/assets/flatpickr.js')}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let activeDays = @json($available_day);
        let slotsByDay = @json($slotsByDay);

        const dayMap = {
            sunday: 0,
            monday: 1,
            tuesday: 2,
            wednesday: 3,
            thursday: 4,
            friday: 5,
            saturday: 6
        };

        let allowedDayIndexes = activeDays.map(d => dayMap[d]);

        // init flatpickr for reschedule
        flatpickr("#slot_date_reschedule", {
            dateFormat: "Y-m-d",
            defaultDate: "{{ $booking->slot_date }}", 
            disable: [
                function(date) {
                    return !allowedDayIndexes.includes(date.getDay());
                }
            ],
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    let date = selectedDates[0];
                    let dayName = Object.keys(dayMap).find(k => dayMap[k] === date.getDay());

                    let slotDropdown = document.getElementById("slot_id_reschedule");
                    slotDropdown.innerHTML = `<option value="">-- Select Slot --</option>`;

                    if (slotsByDay[dayName]) {
                        slotsByDay[dayName].forEach(slot => {
                            let option = document.createElement("option");
                            option.value = slot.id;
                            option.dataset.start = slot.start_time;
                            option.dataset.end = slot.end_time;
                            option.textContent = `${slot.start_time_formatted} to ${slot.end_time_formatted}`;
                            slotDropdown.appendChild(option);
                        });
                    }
                }
            }
        });

        // when slot changes
        document.getElementById("slot_id_reschedule").addEventListener("change", function () {
            let selected = this.options[this.selectedIndex];
            document.getElementById("slot_start_time_reschedule").value = selected.dataset.start || '';
            document.getElementById("slot_end_time_reschedule").value = selected.dataset.end || '';

            let slot_date = document.getElementById("slot_date_reschedule").value;
            let slot_id   = this.value;

            if (slot_date && slot_id) {
                checkSlotAvailability(slot_date, slot_id);
            }
        });

        // AJAX check availability (reuse same route)
        function checkSlotAvailability(slot_date, slot_id) {
            $.post("{{ route('client.slot-booking.checkSlot') }}", {
                _token: "{{ csrf_token() }}",
                slot_date: slot_date,
                slot_id: slot_id,
            }, function(res) {
                if (res.status) {
                    $("#slot_msg_reschedule").removeClass('text-danger')
                        .addClass('text-success')
                        .text(res.message + " ("+(res.booked)+"/"+res.slots+" booked)");
                } else {
                    $("#slot_msg_reschedule").removeClass('text-success')
                        .addClass('text-danger')
                        .text(res.message);
                }
            });
        }
    });


</script>