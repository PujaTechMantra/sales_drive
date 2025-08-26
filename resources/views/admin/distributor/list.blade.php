@extends('layouts/contentNavbarLayout')

@section('title', 'Distributor list')

@section('content')

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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No distributors found for your account.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
</script>
@endsection