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
        $client             = Auth::guard('client')->user();
        $required_day_slots = RequiredDaySlot::select('day', 'slot')->get();
        $available_day      = $required_day_slots->pluck('day')->toArray();
        return view('client.slotBooking.index', compact('client','available_day'));
    }

    public function checkSlot(Request $request)
    {
        $slotDate   = Carbon::parse($request->slot_date);

        // Fetch allowed days and slots
        $rules      = RequiredDaySlot::all();
        $dayName    = strtolower($slotDate->format('l'));

        $rule = $rules->firstWhere('day', $dayName);

        if (!$rule) {
            return response()->json([
                'status'    => false,
                'message'   => 'This day is not available for booking'
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
        // $count = SlotBooking::where('client_id', Auth::guard('client')->id())
        //                         ->whereDate('slot_date', $slotDate)
        //                         ->count();

        if ($count >= $rule->slot) {
            return response()->json([
                'status' => false,
                'message' => 'All ' . $rule->slot . ' slots are full for this date'
            ]);
        }

        return response()->json([
            'status'    => true,
            'message'   => 'Slot available, please enter distributor details',
            'slots'     => $rule->slot,
            'booked'    => $count
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'distributor_name.*'        => 'required|string|max:255',
            'distributor_address.*'     => 'required|string|max:255',
            'distributor_contact_no.*'  => 'required|digits:10',
            'distributor_email.*'       => 'required|email|max:255',
            'slot_date'               => 'required|date',
        ],[
            // Distributor Name
            'distributor_name.*.required' => 'Distributor name is required.',
            'distributor_name.*.string'   => 'Distributor name must be valid text.',
            'distributor_name.*.max'      => 'Distributor name should not exceed 255 characters.',

            // Distributor Address
            'distributor_address.*.required' => 'Distributor address is required.',
            'distributor_address.*.string'   => 'Distributor address must be valid text.',
            'distributor_address.*.max'      => 'Distributor address should not exceed 255 characters.',

            // Distributor Contact
            'distributor_contact_no.*.required' => 'Distributor contact number is required.',
            'distributor_contact_no.*.digits'   => 'Distributor contact number must be exactly 10 digits.',

            // Distributor Email
            'distributor_email.*.required' => 'Distributor email is required.',
            'distributor_email.*.email'    => 'Distributor email must be a valid email address (e.g., name@example.com).',
            'distributor_email.*.max'      => 'Distributor email should not exceed 255 characters.',

            // Slot Date
            'slot_date.required' => 'Slot date is required.',
            'slot_date.date'     => 'Slot date must be a valid date.',
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

        foreach ($request->distributor_name as $index => $name) {
            SlotBooking::create([
                'client_id'              => Auth::guard('client')->id(),
                'distributor_name'       => $name,
                'distributor_address'    => $request->distributor_address[$index],
                'distributor_contact_no' => $request->distributor_contact_no[$index],
                'distributor_email'      => $request->distributor_email[$index],
                'slot_date'              => $slotDate,
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Slot booked successfully!']);
    }

    public function distributorList(Request $request) {
        $clientId = Auth::guard('client')->id();
        $slotDates = SlotBooking::where('client_id', $clientId)
                                        ->select('slot_date')
                                        ->distinct()
                                        ->orderBy('slot_date', 'desc')
                                        ->pluck('slot_date');

        $query = SlotBooking::where('client_id', $clientId);

        if ($request->has('slot_date') && $request->slot_date != '') {
            $query->whereDate('slot_date', $request->slot_date);
        }

        $distributor = $query->get();
        return view('client.slotBooking.list', compact('distributor', 'slotDates'));
    }

   
}
