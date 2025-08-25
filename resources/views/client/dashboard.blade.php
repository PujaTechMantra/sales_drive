

@extends('layouts/contentNavbarLayoutClient')

@section('title', 'Dashboard')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection


@section('content')
 <div class="container-xxl flex-grow-1 container-p-y">

  <h4 class="fw-bold py-3 mb-4">Welcome {{ ucwords(Auth::guard('client')->user()->name) }}</h4>

  <div class="row">
  

  </div>

  <div class="card">
 
  <div class="card-body">
    <canvas height="120"></canvas>
  </div>
</div>

</div>
@endsection
@section('scripts')
@vite('resources/assets/js/dashboards-analytics.js')



@endsection
