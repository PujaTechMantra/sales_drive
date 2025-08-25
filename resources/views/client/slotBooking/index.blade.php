@extends('layouts/contentNavbarLayoutClient')

@section('title', 'Client')

@section('content')

  <div class="container">
    <h3>Slot Booking</h3>

    <form id="slotForm">
        @csrf
        <div class="mb-3">
            <label>Staff Name</label>
            <input type="text" class="form-control" value="{{ ucwords($client->name) }}" readonly>
        </div>

        <div class="mb-3">
            <label>Select Slot Date</label>
            <input type="date" class="form-control" name="slot_date" id="slot_date">
            <div id="slot_msg" class="mt-2"></div>
        </div>

        <div id="distributorSection" style="display:none;">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3"><label>Distributor Name</label>
                    <input type="text" name="distributor_name" id="distributor_name" class="form-control"></div>
                    @error('distributor_name') <p class="text-danger small">{{ $message }}</p> @enderror
                </div>
                <div class="col-md-6">
                    <div class="mb-3"><label>Distributor Address</label>
                    <input type="text" name="distributor_address" id="distributor_address" class="form-control"></div>
                    @error('distributor_address') <p class="text-danger small">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="mb-3"><label>Distributor Address</label>
                    <input type="text" name="distributor_address" id="distributor_address" class="form-control"></div>
                    @error('distributor_address') <p class="text-danger small">{{ $message }}</p> @enderror
                <div class="mb-3"><label>Distributor Contact No</label>
                    <input type="text" name="distributor_contact_no" id="distributor_contact_no" class="form-control"></div>
                    @error('distributor_contact_no') <p class="text-danger small">{{ $message }}</p> @enderror
                <div class="mb-3"><label>Distributor Email</label>
                    <input type="email" name="distributor_email" id="distributor_email" class="form-control"></div>
                    @error('distributor_email') <p class="text-danger small">{{ $message }}</p> @enderror
            </div>

            
        </div>

        <button type="button" id="submitBooking" class="btn btn-primary">Book Slot</button>
    </form>
</div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
        // Check slot availability
            $('#slot_date').on('change', function () {
                let slot_date = $(this).val();
                if (slot_date) {
                    $.post("{{ route('client.slot-booking.checkSlot') }}", {
                        _token: "{{ csrf_token() }}",
                        slot_date: slot_date
                    }, function(res){
                        if (res.status) {
                            $("#slot_msg").removeClass('text-danger').addClass('text-success').text(res.message);
                            $("#distributorSection").show();
                        } else {
                            $("#slot_msg").removeClass('text-success').addClass('text-danger').text(res.message);
                            $("#distributorSection").hide();
                        }
                    });
                }
            });

            // Submit booking
            $('#submitBooking').on('click', function(){
                let formData = $("#slotForm").serialize();
                $.post("{{ route('client.slot-booking.store') }}", formData, function(res){
                    if(res.status){
                        toastFire('success',res.message);
                        location.reload();
                    } else {
                        toastFire('error',res.message);
                    }
                });
            });
        });
        
    </script>
@endsection