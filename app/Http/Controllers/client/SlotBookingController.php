<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\{SlotBooking, RequiredDaySlot, User};
use App\Models\UserActivityLog;

class SlotBookingController extends Controller
{
    
    // public function index() {
    //     $client    = Auth::guard('client')->user();
    //     // $required_day_slots = RequiredDaySlot::select('day', 'slot')->get();
    //     // $required_day_slots = RequiredDaySlot::where('client_id', $client->id)
    //     //                                         ->select('day', 'slot', 'start_time', 'end_time')->get();

    //     $required_day_slots = RequiredDaySlot::where('client_id', $client->id)
    //                                             ->get(['id','day','slot','start_time','end_time'])
    //                                             ->map(function ($slot) {
    //                                                 $slot->start_time_formatted =  date('h:i A', strtotime($slot->start_time));
    //                                                 $slot->end_time_formatted   =  date('h:i A', strtotime($slot->end_time));
    //                                                 return $slot;
    //                                             });

    //     $available_day      = $required_day_slots->pluck('day')->toArray();

    //     $slotsByDay = $required_day_slots->groupBy('day');
                                
    //     return view('client.slotBooking.form', compact('client','available_day', 'required_day_slots', 'slotsByDay'));
    // }

    public function index() 
    {
        $client = Auth::guard('client')->user();

        $required_day_slots = RequiredDaySlot::where('client_id', $client->id)
            ->get(['id','day','slot','start_time','end_time'])
            ->map(function ($slot) use ($client) {
                $slot->start_time_formatted = date('h:i A', strtotime($slot->start_time));
                $slot->end_time_formatted   = date('h:i A', strtotime($slot->end_time));

                $slot->booked = SlotBooking::where('client_id', $client->id)
                    ->whereDate('slot_date', '>=', now()->toDateString()) 
                    ->where('slot_start_time', $slot->start_time)
                    ->where('slot_end_time', $slot->end_time)
                    ->count();

                return $slot;
            });

        $available_day = $required_day_slots->pluck('day')->toArray();
        $slotsByDay    = $required_day_slots->groupBy('day');
                                    
        return view('client.slotBooking.form', compact('client','available_day','required_day_slots','slotsByDay'));
    }


    public function checkSlot(Request $request){
        $slotDate   = Carbon::parse($request->slot_date);

        $slotId = $request->slot_id;

        // Current logged in client
        $clientId = Auth::guard('client')->id();

        // Fetch allowed days and slots
        // $rules      = RequiredDaySlot::all();
        $rule      = RequiredDaySlot::where('client_id', $clientId)->where('id', $slotId)->first();
        // $dayName    = strtolower($slotDate->format('l'));

        // $rule = $rules->firstWhere('day', $dayName);

        if (!$rule) {
            return response()->json([
                'status'    => false,
                'message'   => 'This day is not available for booking'
            ]);
        }

        // Must book 24 hours before 10am
        if ($slotDate->copy()->setTime(10, 0, 0)->subDay()->isPast()) {
            return response()->json([
                'status'    => false,
                'message'   => 'Booking must be done at least 24 hours before the slot'
            ]);
        }

        // Check slot availability
        $count = SlotBooking::whereDate('slot_date', $slotDate)
                                ->where('slot_start_time', $rule->start_time)
                                ->where('slot_end_time', $rule->end_time)
                                ->count();

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

    public function store(Request $request){
        $request->validate([
            'distributor_name.*'            => 'required|string|max:155',
            'distributor_address.*'         => 'required|string|max:255',
            'distributor_contact_no.*'      => 'required|digits:10',
            'distributor_email.*'           => 'required|email|max:100',
            'gst_number.*'                  => 'required|string|max:15',
            'pan_number.*'                  => 'required|string|max:10',
            'distributor_code.*'            => 'required|string|max:50',
            'city.*'                        => 'required|string|max:30',
            'state.*'                       => 'required|string|max:30',
            'zone.*'                        => 'required|string|max:30',
            'distributor_contact_person.*'  => 'nullable|string|max:155',
            'distributor_contact_person_phone.*' => 'nullable|digits:10',
            'so_name.*'                     => 'nullable|string|max:155',
            'so_contact_no.*'               => 'nullable|digits:10',
            'slot_date'                     => 'required|date',
            'slot_start_time' => 'required|date_format:H:i:s',
            'slot_end_time'   => 'required|date_format:H:i:s|after:slot_start_time',               
        ],
       
        [
            // Distributor Name
            'distributor_name.*.required' => 'Distributor name is required.',
            'distributor_name.*.string'   => 'Distributor name must be valid text.',
            'distributor_name.*.max'      => 'Distributor name should not exceed 155 characters.',

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
            'distributor_email.*.max'      => 'Distributor email should not exceed 100 characters.',

            // GST Number
            'gst_number.*.required' => 'GST number is required.',
            'gst_number.*.string'   => 'GST number must be valid text.',
            'gst_number.*.max'      => 'GST number should not exceed 15 characters.',

            //PAN Number
            'pan_number.*.required' => 'PAN number is required.',
            'pan_number.*.string'   => 'PAN number must be valid text.',
            'pan_number.*.max'      => 'PAN number should not exceed 10 characters.',

            //Distributor Code
            'distributor_code.*.required' => 'Distributor code is required.',
            'distributor_code.*.string'   => 'Distributor code must be valid text.',
            'distributor_code.*.max'      => 'Distributor code should not exceed 50 characters.',

            //City
            'city.*.required' => 'City is required.',
            'city.*.string'   => 'City must be valid text.',
            'city.*.max'      => 'City should not exceed 30 characters.',

            //State
            'state.*.required' => 'State is required.',
            'state.*.string'   => 'State must be valid text.',
            'state.*.max'      => 'State should not exceed 30 characters.',

             //Zone
            'zone.*.required' => 'Zone is required.',
            'zone.*.string'   => 'Zone must be valid text.',
            'zone.*.max'      => 'Zone should not exceed 30 characters.',  

            // Slot Date
            'slot_date.required' => 'Slot date is required.',
            'slot_date.date'     => 'Slot date must be a valid date.',

            // Slot Time
            'slot_start_time.required'  => 'Slot start time is required.',

            'slot_end_time.required'    => 'Slot end time is required.',
        ]);
        $slotDate = Carbon::parse($request->slot_date);

        $clientId = Auth::guard('client')->id();

        // Double check before storing
        // $count = SlotBooking::whereDate('slot_date', $slotDate)->count();
        //$rule = RequiredDaySlot::where('day', strtolower($slotDate->format('l')))->first();
        $count = SlotBooking::where('client_id', $clientId)
                                ->whereDate('slot_date', $slotDate)
                                ->count();
        $rule = RequiredDaySlot::where('client_id', $clientId)
                                    ->where('day', strtolower($slotDate->format('l')))
                                    ->first();

        if (!$rule) {
            return response()->json(['status' => false, 'message' => 'Invalid day']);
        }

        if ($count >= $rule->slot) {
            return response()->json(['status' => false, 'message' => 'Sorry, this slot is already full']);
        }

        foreach ($request->distributor_name as $index => $name) {
           $booking = SlotBooking::create([
                'client_id'              => Auth::guard('client')->id(),
                'distributor_name'       => $name,
                'distributor_address'    => $request->distributor_address[$index],
                'distributor_contact_no' => $request->distributor_contact_no[$index],
                'distributor_email'      => $request->distributor_email[$index],
                'gst_number'             => $request->gst_number[$index],
                'pan_number'             => $request->pan_number[$index],
                'distributor_code'       => $request->distributor_code[$index],
                'city'                   => $request->city[$index],
                'state'                  => $request->state[$index],
                'zone'                   => $request->zone[$index],
                'distributor_contact_person'        => $request->distributor_contact_person[$index],
                'distributor_contact_person_phone'  => $request->distributor_contact_person_phone[$index],
                'so_name'               => $request->so_name[$index],
                'so_contact_no'         => $request->so_contact_no[$index],
                'slot_date'             => $slotDate,
                'slot_date_1st'         => $slotDate,
                'slot_start_time'       => $request->slot_start_time,
                'slot_end_time'         => $request->slot_end_time,
            ]);

            UserActivityLog::create([
                'user_id' => Auth::guard('client')->id(),
                'action' => 'slot_booked',
                'user_data' => $booking->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Slot booked successfully!']);
    }

    public function distributorList(Request $request) {
        $clientId = Auth::guard('client')->id();
        $slotDates = SlotBooking::where('client_id', $clientId)
                                        ->orderBy('id', 'desc')
                                        ->pluck('slot_date')->unique();

        $availableDates = SlotBooking::where('client_id', $clientId)
                                        ->pluck('slot_date')
                                        ->unique()
                                        ->map(fn($date)=>Carbon::parse($date)->format('Y-m-d'))
                                        ->values()
                                        ->toArray();

        $query = SlotBooking::with('user')->where('client_id', $clientId);

        if ($request->has('slot_date') && $request->slot_date != '') {
            $query->whereDate('slot_date', $request->slot_date);
        }

        $distributor = $query->orderBy('id', 'desc')->paginate(20);
        return view('client.distributor.list', compact('distributor', 'slotDates', 'availableDates'));
    }

    public function rescheduleForm($id) {
        $booking = SlotBooking::findOrFail($id);

        $required_day_slots = RequiredDaySlot::where('client_id', $booking->client_id)
            ->get(['id','day','slot','start_time','end_time'])
            ->map(function ($slot) use ($booking) {
                $slot->start_time_formatted = date('h:i A', strtotime($slot->start_time));
                $slot->end_time_formatted   = date('h:i A', strtotime($slot->end_time));

                $slot->booked = SlotBooking::where('client_id', $booking->client_id)
                    ->whereDate('slot_date', '>=', now()->toDateString()) 
                    ->where('slot_start_time', $slot->start_time)
                    ->where('slot_end_time', $slot->end_time)
                    ->count();

                return $slot;
            });

        $available_day = $required_day_slots->pluck('day')->toArray();
        $slotsByDay    = $required_day_slots->groupBy('day');

        return view('client.distributor.slot-reschedule', compact('booking','available_day','slotsByDay'));
    }
    // public function rescheduleForm($id) {
    //     $booking = SlotBooking::findOrFail($id);

    //     $required_day_slots = RequiredDaySlot::where('client_id', $booking->client_id)
    //                         ->get(['id','day','slot','start_time','end_time'])
    //                         ->map(function ($slot) {
    //                             $slot->start_time_formatted = date('h:i A', strtotime($slot->start_time));
    //                             $slot->end_time_formatted   = date('h:i A', strtotime($slot->end_time));
    //                             return $slot;

    //                             $slot->booked = SlotBooking::where('client_id', $booking->client_id)
    //                                 ->whereDate('slot_date', '>=', now()->toDateString()) 
    //                                 ->where('slot_start_time', $slot->start_time)
    //                                 ->where('slot_end_time', $slot->end_time)
    //                                 ->count();
    //                         });

    //     $available_day = $required_day_slots->pluck('day')->toArray();
    //     $slotsByDay = $required_day_slots->groupBy('day');

    //     return view('client.distributor.slot-reschedule', compact('booking','available_day','slotsByDay'));
    // }


    // public function saveReschedule(Request $request) {
    //     $request->validate([
    //         'booking_id' => 'required|exists:slot_bookings,id',
    //         'slot_date'  => 'required|date',
    //         'slot_id' => 'required|exists:required_day_slots,id',
    //     ]);

    //     $booking = SlotBooking::findOrFail($request->booking_id);
    //     $slot = RequiredDaySlot::findOrFail($request->slot_id);

    //     $booking->slot_date = $request->slot_date;
    //     // $booking->slot_id = $slot->id;
    //     $booking->slot_start_time = $slot->start_time;
    //     $booking->slot_end_time = $slot->end_time;

    //     $booking->complete_status = 'rescheduled';
    //     $booking->save();

    //     return redirect()->route('client.slot-booking.distributorList')->with('success', 'Slot rescheduled successfully');
    // }

    public function saveReschedule(Request $request) {

        $request->validate([
            'booking_id' => 'required|exists:slot_bookings,id',
            'slot_date'  => 'required|date',
            // 'slot_id' => 'required|exists:required_day_slots,id',
            'distributor_name'            => 'required|string|max:155',
            'distributor_address'         => 'required|string|max:255',
            'distributor_contact_no'      => 'required|digits:10',
            'distributor_email'           => 'required|email|max:100',
            'gst_number'                  => 'required|string|max:15',
            'pan_number'                  => 'required|string|max:10',
            'distributor_code'            => 'required|string|max:50',
            'city'                        => 'required|string|max:30',
            'state'                       => 'required|string|max:30',
            'zone'                        => 'required|string|max:30',
            'distributor_contact_person'  => 'nullable|string|max:155',
            'distributor_contact_person_phone' => 'nullable|digits:10',
            'so_name'                     => 'nullable|string|max:155',
            'so_contact_no'               => 'nullable|digits:10',
            'slot_date'                     => 'required|date',
            
        ]);

        $booking = SlotBooking::findOrFail($request->booking_id);

        // update booking
        $booking->slot_date        = $request->slot_date;
        $booking->slot_start_time  = $request->slot_start_time;
        $booking->slot_end_time    = $request->slot_end_time;

        // distributor details update
        $booking->distributor_code       = $request->distributor_code;
        $booking->distributor_name       = $request->distributor_name;
        $booking->distributor_address    = $request->distributor_address;
        $booking->distributor_contact_no = $request->distributor_contact_no;
        $booking->distributor_email      = $request->distributor_email;
        $booking->pan_number             = $request->pan_number;
        $booking->gst_number             = $request->gst_number;
        $booking->city                   = $request->city;
        $booking->state                  = $request->state;
        $booking->zone                   = $request->zone;
        $booking->distributor_contact_person       = $request->distributor_contact_person;
        $booking->distributor_contact_person_phone = $request->distributor_contact_person_phone;
        $booking->so_name                = $request->so_name;
        $booking->so_contact_no          = $request->so_contact_no;

        $booking->complete_status = 'rescheduled';
        $saved = $booking->save();
        // dd($saved);

        UserActivityLog::create([
            'user_id' => Auth::guard('client')->id(),
            'action' => 'slot_rescheduled',
            'user_data' => $booking->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()
            ->route('client.slot-booking.distributorList')
            ->with('success', 'Slot rescheduled successfully');
    }


   
}
