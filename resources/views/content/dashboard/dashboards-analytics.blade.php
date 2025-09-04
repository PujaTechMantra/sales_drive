

@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <h4 class="fw-bold py-3 mb-4">Welcome to Sales Drive Application</h4>

  <div class="row">
    {{-- date filter --}}
      <form method="GET" action="{{route('admin.dashboard')}}" class="mt-2">
        <div class="row mb-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="col-md-6"></div>
              <div class="form-group d-flex align-items-center mb-0">        
                {{-- <select name="slot_date" id="slot_date" class="form-control form-control-sm select2 me-2" style="width: 180px;">
                  <option value="">-- All Dates --</option>
                  @foreach($slotDates as $date)
                    <option value="{{ $date}}" {{ request('slot_date') == $date ? 'selected' : ''}}>
                      {{ date('d-m-Y',strtotime($date))}}
                    </option>
                  @endforeach
                </select> --}}
                <input type="text" class="form-control" name="slot_date" id="slot_date"
                  placeholder="Select slot date" autocomplete="off" value="{{ request('slot_date')}}" 
                  style="height: 30px">
                <button type="submit" class="btn btn-sm btn-primary me-1" style="height: 30px">Filter</button>
                <a href="{{ url()->current() }}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Clear filter">
                    <i class="tf-icons ri-close-line"></i>
                </a>
              </div>
          </div>
       </div>
      </form>

      {{-- clients --}}
      <div class="col-md-4">
        <div class="card text-center shadow-sm bg-primary text-white">
          <div class="card-body">
            <h5 class="card-title">Total Clients</h5>
            <h2 class="fw-bold">{{ $totalClients }}</h2>
          </div>
        </div>
      </div>

      {{-- distributors --}}
      <div class="col md-4">
        <div class="card text-center shadow-sm bg-info text-white">
          <div class="card-body">
            <h5 class="card-title">Total Disributors</h5>
            <h2 class="fw-bold">{{ $totalDistributors}}</h2>
          </div>
        </div>
      </div>

      {{-- slots --}}
      <div class="col-md-4">
        <div class="card text-center shadow-sm bg-secondary text-white">
          <div class="card-body">
            <h5 class="card-title">Total Slots
              @if(request()->filled('slot_date'))
                ({{ request('slot_date')}})
              @endif
            </h5>
            <h2 class="fw-bold"> {{ $totalSlots}}</h2>            
          </div>
        </div>
      </div>     
  </div>

</div>

</div>
@endsection
@section('scripts')
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
<link rel="stylesheet" href="{{ asset('build/assets/flatpickr.min.css') }}">
<script src="{{ asset('build/assets/flatpickr.js')}}"></script>
@vite('resources/assets/js/dashboards-analytics.js')
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
  $(document).ready(function() {
      $('#slot_date').select2({
          placeholder: "Select Slot Date",
          allowClear: true
      });
  });
</script>

@endsection
