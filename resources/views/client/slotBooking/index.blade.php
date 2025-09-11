@extends('layouts/contentNavbarLayoutClient')

@section('title', 'Slot booking form')

@section('content')

{{-- <div class="container">
    <h3>Slot Booking</h3>

    <form id="slotForm">
        @csrf
        <div class="mb-3">
            <label>Staff Name</label>
            <input type="text" class="form-control" value="{{ ucwords($client->name) }}" readonly>
        </div>

        <div class="row d-flex">
            <div class="col-md-4 mb-3">
                <label for="slot_date" class="form-label">Slot Date</label>
                <input type="text" class="form-control" name="slot_date" id="slot_date" placeholder="Select slot date" autocomplete="off">
                <small id="slot_msg" class="d-block mt-1"></small>
            </div>
            <div class="col-md-4 mb-3">
                <label for="slot_start_time">Slot Start Time</label>
                <input type="time" id="slot_start_time" name="slot_start_time" class="form-control" value="">
                @error('slot_start_time') <p class="text-danger small">{{ $message }}</p> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="slot_end_time">Slot End Time</label>
                <input type="time" id="slot_end_time" name="slot_end_time" class="form-control" value="">
                @error('slot_end_time') <p class="text-danger small">{{ $message }}</p> @enderror
            </div>

        </div>
        <div id="distributorContainer">
           
            <div class="distributor-section border rounded p-4 mb-4 position-relative">
                <button type="button" class="btn btn-sm btn-danger removeDistributor position-absolute top-0 end-0 m-0"  style="display:none;">✖ </button>
                    
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3"><label>Distributor Code(SAP)</label>
                            <input type="text" name="distributor_code[]" id="distributor_code" class="form-control">
                        </div>
                        @error('distributor_code') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3"><label>Distributor Name</label>
                        <input type="text" name="distributor_name[]" id="distributor_name" class="form-control"></div>
                        @error('distributor_name') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3"><label>Full Address</label>
                            <textarea name="distributor_address[]" id="distributor_address" rows="2" class="form-control"></textarea>
                        </div>                        
                        @error('distributor_address') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3"><label>Distributor Phone No</label>
                            <input type="text" name="distributor_contact_no[]" id="distributor_contact_no" class="form-control">
                        </div>
                        @error('distributor_contact_no') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-3">                
                        <div class="mb-3"><label>Distributor Email ID</label>
                            <input type="email" name="distributor_email[]" id="distributor_email" class="form-control">
                        </div>
                        @error('distributor_email') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3"><label>Distributor Pan Number</label>
                            <input type="text" name="pan_number[]" id="pan_number" class="form-control">
                        </div>
                        @error('pan_number') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-3">                
                        <div class="mb-3"><label>Distributor GST Number</label>
                            <input type="text" name="gst_number[]" id="gst_number" class="form-control">
                        </div>
                        @error('gst_number') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3"><label>City</label>
                            <input type="text" name="city[]" id="city" class="form-control">
                        </div>
                        @error('city') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-4">                
                        <div class="mb-3"><label>State</label>
                            <input type="text" name="state[]" id="state" class="form-control">
                        </div>
                        @error('state') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-4">                
                        <div class="mb-3"><label>Zone</label>
                            <input type="text" name="zone[]" id="zone" class="form-control">
                        </div>
                        @error('zone') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">                
                        <div class="mb-3"><label>Distributor Contact Person (optional)</label>
                            <input type="text" name="distributor_contact_person[]" id="distributor_contact_person" class="form-control">
                        </div>
                        @error('distributor_contact_person') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3"><label>Distributor Contact Person Phone No (optional)</label>
                            <input type="text" name="distributor_contact_person_phone[]" id="distributor_contact_person_phone" class="form-control">
                        </div>
                        @error('distributor_contact_person_phone') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3"><label>SO Name (optional)</label>
                            <input type="text" name="so_name[]" id="so_name" class="form-control">
                        </div>
                        @error('so_name') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-6">                
                        <div class="mb-3"><label>SO Contact Number (optional)</label>
                            <input type="text" name="so_contact_no[]" id="so_contact_no" class="form-control">
                        </div>
                        @error('so_contact_no') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                </div>                
            </div>
        </div>

        <button type="button" id="addDistributor" class="btn btn-secondary mt-2" style="display:none;">+ Add Distributor
        </button>
        <button type="button" id="submitBooking" class="btn btn-primary mt-2">Book Slot</button>
    </form>
</div> --}}
<div class="container my-4">
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
                    <div class="col-md-4">
                        <label for="slot_date" class="form-label fw-semibold">Slot Date</label>
                        <input type="text" class="form-control" name="slot_date" id="slot_date" placeholder="Select slot date" autocomplete="off">
                        <small id="slot_msg" class="text-danger"></small>
                    </div>
                    <div class="col-md-4">
                        <label for="slot_start_time" class="form-label fw-semibold">Start Time</label>
                        <input type="time" id="slot_start_time" name="slot_start_time" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="slot_end_time" class="form-label fw-semibold">End Time</label>
                        <input type="time" id="slot_end_time" name="slot_end_time" class="form-control">
                    </div>
                </div>

                <!-- Distributor Section -->
                <div id="distributorContainer">
                    <div class="distributor-section bg-light-subtle border rounded-3 p-4 mb-4 position-relative">
                        <button type="button" class="btn btn-sm btn-outline-danger removeDistributor position-absolute top-0 end-0 m-2" style="display:none;">✖</button>

                        <h5 class="fw-bold mb-3 text-primary">Distributor Information</h5>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Distributor Code (SAP)</label>
                                <input type="text" name="distributor_code[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Name</label>
                                <input type="text" name="distributor_name[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Full Address</label>
                                <textarea name="distributor_address[]" rows="2" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="distributor_contact_no[]" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="distributor_email[]" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PAN</label>
                                <input type="text" name="pan_number[]" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">GST</label>
                                <input type="text" name="gst_number[]" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Zone</label>
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
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
<link rel="stylesheet" href="{{ asset('build/assets/flatpickr.min.css') }}">
<script src="{{ asset('build/assets/flatpickr.js')}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let activeDays = @json($available_day); // e.g. ["tuesday","wednesday","thursday"]

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
            ]
        });
    });
     
    
    $(document).ready(function(){
        let maxSlots = 0;
        let bookedCount = 0;
        let currentAdded = 0;
        let slotInterval = null;

        function checkSlotAvailability(slot_date){
            $.post("{{ route('client.slot-booking.checkSlot') }}", {
                _token: "{{ csrf_token() }}",
                slot_date: slot_date
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

        // When date is selected
        $('#slot_date').on('change', function () {
            let slot_date = $(this).val();
            if (!slot_date) return;
  
            if (slotInterval) clearInterval(slotInterval); // clear any previous interval

            checkSlotAvailability(slot_date);  // run once immediately

            // keep checking every 30s
            slotInterval = setInterval(function(){
                checkSlotAvailability(slot_date);
            }, 30000);
        });

        $('#addDistributor').on('click', function(){
            if (currentAdded + bookedCount >= maxSlots) {
                $("#slot_msg").removeClass('text-success').addClass('text-danger')
                    .text("All slots are full for this date.");
                return;
            }
            let newSection = $('.distributor-section:first').clone();

            //textarea
            newSection.find("textarea").val("");

            // Clear input values
            newSection.find("input").val("");

            // Remove old validation errors
            newSection.find(".text-danger").remove();

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

        // $('#submitBooking').on('click', function(){
        //     if (slotInterval) clearInterval(slotInterval); // stop checking once user submits

        //     let slot_date = $("#slot_date").val();
        //     if (!slot_date) {
        //         $("#slot_msg").removeClass('text-success').addClass('text-danger')
        //             .text("Please select the date before booking.");
        //         return;
        //     }

        //     // Loop through each distributor-section and collect data with index
        //     let distributors = [];
        //     $('#distributorContainer .distributor-section').each(function(index, section){
        //         distributors.push({
        //             distributor_code: $(section).find('[name="distributor_code[]"]').val(),
        //             distributor_name: $(section).find('[name="distributor_name[]"]').val(),
        //             distributor_address: $(section).find('[name="distributor_address[]"]').val(),
        //             distributor_contact_no: $(section).find('[name="distributor_contact_no[]"]').val(),
        //             distributor_email: $(section).find('[name="distributor_email[]"]').val(),
        //             pan_number: $(section).find('[name="pan_number[]"]').val(),
        //             gst_number: $(section).find('[name="gst_number[]"]').val(),
        //             city: $(section).find('[name="city[]"]').val(),
        //             state: $(section).find('[name="state[]"]').val(),
        //             zone: $(section).find('[name="zone[]"]').val(),
        //             distributor_contact_person: $(section).find('[name="distributor_contact_person[]"]').val(),
        //             distributor_contact_person_phone: $(section).find('[name="distributor_contact_person_phone[]"]').val(),
        //             so_name: $(section).find('[name="so_name[]"]').val(),
        //             so_contact_no: $(section).find('[name="so_contact_no[]"]').val()
        //         });
        //     });

        //     let formData = {
        //         _token: $('input[name="_token"]').val(),
        //         slot_date: $("#slot_date").val(),
        //         slot_start_time: $("#slot_start_time").val(),
        //         slot_end_time: $("#slot_end_time").val(),
        //         distributors: distributors
        //     };

        //     $.ajax({
        //         url: "{{ route('client.slot-booking.store') }}",
        //         type: "POST",
        //         contentType: "application/json",   // important
        //         data: JSON.stringify(formData),    // send JSON
        //         success: function(res){
        //             if(res.status){
        //                 toastFire('success', res.message);
        //                 location.reload();
        //             } else {
        //                 toastFire('error', res.message);
        //             }
        //         },
        //         error: function(xhr){
        //             if(xhr.status === 422){
        //                 $('.text-danger').remove();
        //                 $.each(xhr.responseJSON.errors, function(field, messages){
        //                     // Laravel will now return errors like distributors.0.distributor_code
        //                     let match = field.match(/^distributors\.(\d+)\.(.+)$/);
        //                     if(match){
        //                         let index = match[1];
        //                         let fieldName = match[2];
        //                         let input = $('#distributorContainer .distributor-section')
        //                                     .eq(index)
        //                                     .find('[name="'+fieldName+'[]"]');
        //                         input.after('<p class="text-danger small">'+messages[0]+'</p>');
        //                     }
        //                 });
        //             }
        //         }
        //     });
        // });


    });

</script>
@endsection