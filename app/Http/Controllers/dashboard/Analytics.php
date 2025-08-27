<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Admin, SlotBooking};

class Analytics extends Controller
{
  
    public function index(Request $request){
    
        $slotDates = SlotBooking::select('slot_date')
        ->distinct()
        ->orderBy('slot_date', 'desc')
        ->pluck('slot_date');

        // apply filter only if date is selected
        if ($request->filled('slot_date')) {
            $date = $request->slot_date;

            $totalClients = SlotBooking::whereDate('slot_date', $date)
                ->distinct('client_id')
                ->count('client_id');

            $totalDistributors = SlotBooking::whereDate('slot_date', $date)
                ->distinct('distributor_name')
                ->count('distributor_name');

            $totalSlots = SlotBooking::whereDate('slot_date', $date)->count();
        } else {
            // no filter = count everything
            $totalClients = SlotBooking::distinct('client_id')->count('client_id');
            $totalDistributors = SlotBooking::distinct('distributor_name')->count('distributor_name');
            $totalSlots = SlotBooking::count();
        }

        return view('content.dashboard.dashboards-analytics', compact('totalClients', 'totalDistributors', 'totalSlots', 'slotDates'
        ));
    }


    
}
