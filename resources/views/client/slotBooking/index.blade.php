@extends('layouts/contentNavbarLayoutClient')

@section('title', 'Slot booking form')

@section('content')

  <div class="container">
    <h3>Slot Booking</h3>

    <form id="slotForm">
        @csrf
        <div class="mb-3">
            <label>Staff Name</label>
            <input type="text" class="form-control" value="{{ ucwords($client->name) }}" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="slot_date" class="form-label">Select Slot Date</label>
            <input type="text" class="form-control" name="slot_date" id="slot_date" placeholder="Select slot date" autocomplete="off">

            <small id="slot_msg" class="d-block mt-1"></small>
        </div>
        <div id="distributorContainer">
            {{-- <div id="distributorSection" style="display:none;"> --}}
            <div class="distributor-section border rounded p-4 mb-4 position-relative">
                <button type="button" class="btn btn-sm btn-danger removeDistributor position-absolute top-0 end-0 m-0"  style="display:none;">✖ </button>
                    
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3"><label>Distributor Name</label>
                        <input type="text" name="distributor_name[]" id="distributor_name" class="form-control"></div>
                        @error('distributor_name') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3"><label>Distributor Address</label>
                        <input type="text" name="distributor_address[]" id="distributor_address" class="form-control"></div>
                        @error('distributor_address') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3"><label>Distributor Contact No</label>
                            <input type="text" name="distributor_contact_no[]" id="distributor_contact_no" class="form-control">
                        </div>
                        @error('distributor_contact_no') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-6">                
                        <div class="mb-3"><label>Distributor Email</label>
                            <input type="email" name="distributor_email[]" id="distributor_email" class="form-control">
                        </div>
                        @error('distributor_email') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3"><label>Distributor Pan Number</label>
                            <input type="text" name="pan_number[]" id="pan_number" class="form-control">
                        </div>
                        @error('pan_number') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-md-6">                
                        <div class="mb-3"><label>Distributor GST Number</label>
                            <input type="text" name="gst_number[]" id="gst_number" class="form-control">
                        </div>
                        @error('gst_number') <p class="text-danger small">{{ $message }}</p> @enderror
                    </div>
                </div>
                
            </div>
        </div>

        <button type="button" id="addDistributor" class="btn btn-secondary mt-2" style="display:none;">+ Add Distributor
        </button>
        <button type="button" id="submitBooking" class="btn btn-primary mt-2">Book Slot</button>
    </form>
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
            newSection.find("input").val(""); 
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