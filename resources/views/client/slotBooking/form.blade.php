@extends('layouts/contentNavbarLayoutClient')

@section('title', 'Slot booking form')

@section('content')

<div class="container">
    <div class="card shadow-lg border-0 rounded-2">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0 text-center">📅 Slot Booking Form</h4>
        </div>

        <div class="card-body p-4">
            <form id="slotForm">
                @csrf
                <!-- Staff -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Staff Name</label>
                    <input type="text" class="form-control" value="{{ ucwords($client->name) }}" readonly>
                </div>

                <!-- Slot Details -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="slot_date" class="form-label fw-semibold">Slot Date</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" name="slot_date" id="slot_date" placeholder="Select slot date" autocomplete="off">
                        <small id="slot_msg" class="text-danger"></small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select Time Slot</label> <span class="text-danger">*</span>
                        <select name="slot_id" id="slot_id" class="form-control">
                            <option value="">-- Select Slot --</option>
                        </select>
                        <input type="hidden" name="slot_start_time" id="slot_start_time">
                        <input type="hidden" name="slot_end_time" id="slot_end_time">
                    </div>
                </div>

                <!-- Distributor Section -->
                <div id="distributorContainer">
                    <div class="distributor-section bg-light-subtle border rounded-3 p-4 mb-4 position-relative">
                        <button type="button" class="btn btn-sm btn-outline-danger removeDistributor position-absolute top-0 end-0 m-2" style="display:none;">✖</button>

                        <h5 class="fw-bold mb-3 text-primary">Distributor Information</h5>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Distributor Code (SAP)</label> <span class="text-danger">*</span>
                                <input type="text" name="distributor_code[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Name</label> <span class="text-danger">*</span>
                                <input type="text" name="distributor_name[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Full Address</label> <span class="text-danger">*</span>
                                <textarea name="distributor_address[]" rows="2" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Phone</label> <span class="text-danger">*</span>
                                <input type="text" name="distributor_contact_no[]" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email</label> <span class="text-danger">*</span> 
                                <input type="email" name="distributor_email[]" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PAN</label> <span class="text-danger">*</span>
                                <input type="text" name="pan_number[]" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">GST</label> <span class="text-danger">*</span>
                                <input type="text" name="gst_number[]" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">City</label> <span class="text-danger">*</span>
                                <input type="text" name="city[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label> <span class="text-danger">*</span>
                                <input type="text" name="state[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Zone</label> <span class="text-danger">*</span>
                                <input type="text" name="zone[]" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Person (optional)</label>
                                <input type="text" name="distributor_contact_person[]" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Person Phone (optional)</label>
                                <input type="text" name="distributor_contact_person_phone[]" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">SO Name (optional)</label>
                                <input type="text" name="so_name[]" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SO Contact No (optional)</label>
                                <input type="text" name="so_contact_no[]" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" id="addDistributor" class="btn btn-outline-secondary" style="display:none;">+ Add Distributor</button>
                    <button type="button" id="submitBooking" class="btn btn-primary px-4">Book Slot</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')

<link rel="stylesheet" href="{{ asset('build/assets/flatpickr.min.css') }}">
<script src="{{ asset('build/assets/flatpickr.js')}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let activeDays = @json($available_day); // e.g. ["tuesday","wednesday","thursday"]
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
        
        flatpickr("#slot_date", {
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    // disable days not in allowedDayIndexes
                    return !allowedDayIndexes.includes(date.getDay());
                }
            ],
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    let date = selectedDates[0];
                    let dayName = Object.keys(dayMap).find(k => dayMap[k] === date.getDay());

                    //Populate slots for that day
                    let slotDropdown = document.getElementById("slot_id");
                    slotDropdown.innerHTML = `<option value="">-- Select Slot --</option>`;

                    if(slotsByDay[dayName]) {
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
         // When slot changes, set hidden start/end time
        document.getElementById("slot_id").addEventListener("change", function() {
            let selected = this.options[this.selectedIndex];
            document.getElementById("slot_start_time").value = selected.dataset.start || '';
            document.getElementById("slot_end_time").value = selected.dataset.end || '';
        });
    });
     
    
    $(document).ready(function(){
        let maxSlots = 0;
        let bookedCount = 0;
        let currentAdded = 0;
        let slotInterval = null;

        // function checkSlotAvailability(slot_date){
        //     $.post("{{ route('client.slot-booking.checkSlot') }}", {
        //         _token: "{{ csrf_token() }}",
        //         slot_date: slot_date
        //     }, function(res){
        //         if (res.status) {
        //             $("#slot_msg").removeClass('text-danger').addClass('text-success')
        //                 .text(res.message + " ("+(res.booked)+"/"+res.slots+" booked)");
        //             $("#distributorContainer").show();
        //             $("#addDistributor").show();
        //             maxSlots = res.slots; 
        //             bookedCount = res.booked ?? 0; 
        //             currentAdded = 1;
        //         } else {
        //             $("#slot_msg").removeClass('text-success').addClass('text-danger').text(res.message);
        //             $("#distributorContainer").hide();
        //             $("#addDistributor").hide();
        //         }
        //     });
        // }

        // When date is selected
        // $('#slot_date').on('change', function () {
        //     let slot_date = $(this).val();
        //     if (!slot_date) return;
  
        //     if (slotInterval) clearInterval(slotInterval); // clear any previous interval

        //     checkSlotAvailability(slot_date);  // run once immediately

        //     // keep checking every 30s
        //     slotInterval = setInterval(function(){
        //         checkSlotAvailability(slot_date);
        //     }, 30000);
        // });

        $('#slot_date').on('change', function () {
            let slot_date = $(this).val();
            if(!slot_date) return;

            //reset slot dropdown
            $("#slot_id").val("");
            $("#slot_msg").text("");
        });

        //when slot is selected
        $('#slot_id').on('change', function () {
            let slot_date = $("#slot_date").val();
            let slot_id   = $(this).val();
            if(!slot_date || !slot_id) return;

            if(slotInterval) clearInterval(slotInterval);

            checkSlotAvailability(slot_date, slot_id);

            slotInterval = setInterval(function() {
                checkSlotAvailability(slot_date, slot_id)
            }, 30000);
        });

        function checkSlotAvailability(slot_date, slot_id){
            $.post("{{ route('client.slot-booking.checkSlot') }}", {
                _token: "{{ csrf_token() }}",
                slot_date: slot_date,
                slot_id: slot_id,
            }, function(res){
                if (res.status) {
                    $("#slot_msg").removeClass('text-danger').addClass('text-success')
                        .text(res.message + " ("+(res.booked)+"/"+res.slots+" booked)");
                    $("#distributorContainer").show();
                    $("#addDistributor").show();
                    maxSlots = res.slots; 
                    bookedCount = res.booked ?? 0; 
                    currentAdded = 1;
                } else {
                    $("#slot_msg").removeClass('text-success').addClass('text-danger').text(res.message);
                    $("#distributorContainer").hide();
                    $("#addDistributor").hide();
                }
            });
        }


        $('#addDistributor').on('click', function(){
            if (currentAdded + bookedCount >= maxSlots) {
                $("#slot_msg").removeClass('text-success').addClass('text-danger')
                    .text("All slots are full for this date.");
                return;
            }
            let newSection = $('.distributor-section:first').clone();

            // Clear inputs/textarea
            newSection.find("input").val("");
            newSection.find("textarea").val("");

            // Remove old error messages (but not * in labels)
            newSection.find("p.text-danger").remove();

            // Show remove button for cloned sections
            newSection.find('.removeDistributor').show();

            $('#distributorContainer').append(newSection);
            currentAdded++;
        });



        $(document).on('click', '.removeDistributor', function() {
            $(this).closest('.distributor-section').remove();
            currentAdded--;
        });

        $('#submitBooking').on('click', function(){
            if (slotInterval) clearInterval(slotInterval); // stop checking once user submits

            let slot_date = $("#slot_date").val();
            if (!slot_date) {
                $("#slot_msg").removeClass('text-success').addClass('text-danger')
                    .text("Please select the date before booking.");
                return;
            }

            let formData = $("#slotForm").serialize();
            $.ajax({
                url: "{{ route('client.slot-booking.store') }}",
                type: "POST",
                data: formData,
                success: function(res){
                    if(res.status){
                        toastFire('success',res.message);
                        location.reload();
                    } else {
                        toastFire('error',res.message);
                    }
                },
                error: function(xhr){
                    if(xhr.status === 422){ 
                        $('.text-danger').remove(); 
                        $.each(xhr.responseJSON.errors, function(field, messages){
                            let fieldName = field.replace(/\.\d+$/, '[]');
                            let input = $("[name='" + fieldName + "']");
                            if(field.match(/\.\d+$/)){
                                let index = field.match(/\d+$/)[0];
                                input = $("[name='" + fieldName + "']").eq(index);
                            }
                            input.after('<p class="text-danger small">'+messages[0]+'</p>');
                        });
                    }
                }
            });
        });

    });

</script>
@endsection