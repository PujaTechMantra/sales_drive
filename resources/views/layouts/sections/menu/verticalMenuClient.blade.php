<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <div class="app-brand demo">
      <img src="{{asset('assets/img/logo-tm.png')}}" alt="" style="width: 60px; height: auto;">
      <span class="app-brand-text demo menu-text fw-semibold ms-2">Sales Drive</span>
  </div>

  <div class="menu-inner-shadow"></div>

  <div class="menu-part">
    <ul class="menu-inner py-1 ps">

      {{-- Client Dashboard --}}
      <li class="menu-item {{ (request()->is('client/dashboard*')) ? 'open' : '' }}">
        <a href="{{route('client.dashboard')}}" class="menu-link">
          <i class="menu-icon fa-solid fa-house"></i>
          <div>Dashboard</div>
        </a>
      </li>

      <li class="menu-item {{ (request()->is('client/slot-booking*')) ? 'open' : '' }}">
        <a href="javascript:void(0)" class="menu-link menu-toggle waves-effect" target="_blank">
          <i class="menu-icon fa-solid fa-house"></i>
          <div>Slot Booking</div>
        </a>
        <ul class="menu-sub"> 
          <li class="menu-item {{ (request()->is('client/slot-booking/form')) ? 'open' : '' }}">
            <a href="{{route('client.slot-booking.index')}}" class="menu-link">
              <div>Slot Booking Form</div>
            </a>
          </li>   
        </ul>

        <ul class="menu-sub"> 
          <li class="menu-item {{ (request()->is('client/slot-booking/list')) ? 'open' : '' }}">
            <a href="{{ route('client.slot-booking.distributorList')}}" class="menu-link">
              <div>Distributor List</div>
            </a>
          </li>   
        </ul>
      </li>


      {{-- Logout --}}
      <li class="menu-item">
        <a class="btn btn-danger d-flex rounded-0" href="{{ route('client.logout') }}"
            onclick="event.preventDefault(); document.getElementById('client-logout-form').submit();">
              <small class="align-middle">Logout</small>
              <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
          </a>
      </li>
      <form id="client-logout-form" action="{{ route('client.logout') }}" method="POST" class="d-none">
            @csrf
      </form>

    </ul>
  </div>
</aside>
