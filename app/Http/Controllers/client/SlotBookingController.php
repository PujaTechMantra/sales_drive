<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\{SlotBooking, RequiredDaySlot};

class SlotBookingController extends Controller
{
    //
    public function index() {
        $client = Auth::guard('client')->user();
        return view('client.slotBooking.index', compact('client'));
    }

    public function checkSlot(Request $request)
    {
        $slotDate = Carbon::parse($request->slot_date);

        // Fetch allowed days and slots
        $rules = RequiredDaySlot::all();
        $dayName = strtolower($slotDate->format('l'));

        $rule = $rules->firstWhere('day', $dayName);

        if (!$rule) {
            return response()->json([
                'status' => false,
                'message' => 'This day is not available for booking'
            ]);
        }

        // Must book 24 hours before 10am
        if ($slotDate->copy()->setTime(10, 0, 0)->subDay()->isPast()) {
            return response()->json([
                'status' => false,
                'message' => 'Booking must be done at least 24 hours before the slot'
            ]);
        }

        // Check slot availability
        $count = SlotBooking::whereDate('slot_date', $slotDate)->count();

        if ($count >= $rule->slot) {
            return response()->json([
                'status' => false,
                'message' => 'All ' . $rule->slot . ' slots are full for this date'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Slot available, please enter distributor details'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'distributor_name'        => 'required|string|max:255',
            'distributor_address'     => 'required|string|max:255',
            'distributor_contact_no'  => 'required|digits_between:10,15', // only numbers
            'distributor_email'       => 'required|email|max:255',
            'slot_date'               => 'required|date',
        ]);
        $slotDate = Carbon::parse($request->slot_date);

        // Double check before storing
        $count = SlotBooking::whereDate('slot_date', $slotDate)->count();
        $rule = RequiredDaySlot::where('day', strtolower($slotDate->format('l')))->first();

        if (!$rule) {
            return response()->json(['status' => false, 'message' => 'Invalid day']);
        }

        if ($count >= $rule->slot) {
            return response()->json(['status' => false, 'message' => 'Sorry, this slot is already full']);
        }

        SlotBooking::create([
            'client_id'              => Auth::guard('client')->id(),
            'distributor_name'       => $request->distributor_name,
            'distributor_address'    => $request->distributor_address,
            'distributor_contact_no' => $request->distributor_contact_no,
            'distributor_email'      => $request->distributor_email,
            'slot_date'              => $slotDate,
        ]);

        return response()->json(['status' => true, 'message' => 'Slot booked successfully!']);
    }
}
