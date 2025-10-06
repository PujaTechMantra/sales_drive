<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, SlotBooking, SiteReadinessForm, RequiredDaySlot};


class ClientListController extends Controller
{
    // //
    // public function index(Request $request){
    //     $keyword = $request->input('keyword');

    //     $query = User::query();
    //     $query->when($keyword, function ($q) use ($keyword) {
    //         $q->where(function($subQuery) use ($keyword) {
    //             $subQuery->where('name', 'like', '%' . $keyword . '%')
    //                 ->orWhere('email', 'like', '%' . $keyword . '%')
    //                 ->orWhere('address', 'like', '%' . $keyword . '%')
    //                 ->orWhere('mobile_no', 'like', '%' . $keyword . '%');
    //         });
    //     });

    //     $client = $query->latest('id')->paginate(10);

    //     return view('admin.clients.index', compact('client'));
    // }

    public function index(Request $request) {
        $keyword = $request->input('keyword');

        $query = User::query();
        $query->when($keyword, function ($q) use ($keyword) {
            $q->where(function($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('address', 'like', '%' . $keyword . '%')
                    ->orWhere('mobile_no', 'like', '%' . $keyword . '%');
            });
        });

        $clients = $query->latest('id')->paginate(10);

        // Fetch distributors with reschedules
        $distributors = \App\Models\SlotBooking::with(['user', 'reschedules'])->paginate(10);

        return view('admin.clients.index', compact('clients', 'distributors'));
    }


    public function store(Request $request) {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'mobile_no' => 'required|string|max:20|unique:users,mobile_no',
            'address'   => 'nullable|string',
            'password'  => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'mobile_no' => $validated['mobile_no'],
            'address'   => $validated['address'] ?? null,
            'password'  => bcrypt($validated['password']),
        ]);

        return response()->json([
            'success' => 200,
            'data'    => $user
        ]);
    }

    public function edit($id) {
        $client = User::findOrFail($id);
        return response()->json($client);
    }

    public function update(Request $request) {
        $request->validate([
            'id'        => 'required|exists:users,id',
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'mobile_no' => 'nullable|string|max:20',
            'address'   => 'nullable|string',
        ]);

        $client = User::findOrFail($request->id);
        $client->update($request->only(['name', 'email', 'address', 'mobile_no']));
        return response()->json(['success' => true, 'message' => 'Client updated successfully']);
    }

    public function status($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status ? 0 : 1;
        $user->save();
        return response()->json([
            'status'  => 200,
            'message' => 'Status updated successfully'
        ]);
    }

    public function delete(Request $request){
        $client = User::find($request->id);

        $client->delete();
        return response()->json([
            'status'    => 200,
            'message'   => 'Client data deleted successfully'
        ]);
    }

    public function trainingStatus($id) {
        $user = User::findOrFail($id);

        $user->training_status = $user->training_status ? 0 : 1;
        $user->save();
        return response()->json([
            'status'    => 200,
            'message'   => 'Training status updated successfully'
        ]);
    }

    public function getSlotdate($id){
        $slots = RequiredDaySlot::where('client_id', $id)->get();
        $days  = RequiredDaySlot::select('day')->distinct()->pluck('day')->toArray();

        if (empty($days)) {
            $days = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
        }

        return response()->json([
            'slots' => $slots,
            'days'  => $days,
        ]);
    }

   
    public function saveSlotdate(Request $request){
        //dd($request->all());
        $client_id      = $request->client_id;
        $days           = $request->day ?? [];
        $slots          = $request->slot ?? [];
        $start_times    = $request->start_time ?? [];
        $end_times      = $request->end_time ?? [];

        $errors = [];

        // Validate required
        foreach($days as $i => $day){
            if(empty($day)){
                $errors['day'][$i] = "The day field is required.";
            }
            if(!isset($slots[$i]) || $slots[$i] === ''){
                $errors['slot'][$i] = "Please add slot for {$day}.";
            }
            if (empty($start_times[$i])) {
                $errors['start_time'][$i] = "Please add start time for {$day}.";
            }
            if (empty($end_times[$i])) {
                $errors['end_time'][$i] = "Please add end time for {$day}.";
            }
        }

        // Validate duplicate days
        // $dayCounts = array_count_values($days);
        // foreach($dayCounts as $day => $count){
        //     if($count > 1){
        //         $errors['day'][] = "That '$day' is duplicated. please select another day";
        //     }
        // }

        //check overlapping slots within the same day
        $dayWiseSlots = [];
        foreach($days as $i => $day){
            if(!isset($dayWiseSlots[$day])) {
                $dayWiseSlots[$day] = [];
            }
            $dayWiseSlots[$day][] = [
                'start' => $start_times[$i],
                'end'   => $end_times[$i],
                'index' => $i
            ];
        }

        foreach ($dayWiseSlots as $day => $slotsForDay) {
            usort($slotsForDay, fn($a, $b) => strcmp($a['start'], $b['start']));

            for ($j = 0; $j< count($slotsForDay) - 1; $j++) {
                $current = $slotsForDay[$j];
                $next    = $slotsForDay[$j + 1];

                if ($current['end'] > $next['start']) {
                    $errors['time'][$current['index']] = "This time slot is already booked for {$day}, please choose another slot.";
                    $errors['time'][$next['index']] = "This time slot is already booked for {$day}, please choose another slot.";
                }
            }
        }


        if(!empty($errors)){
            return response()->json(['errors' => $errors], 422);
        }

        // Delete old slots
        RequiredDaySlot::where('client_id', $client_id)->delete();

        // Save new slots
        foreach($days as $i => $day){
            RequiredDaySlot::create([
                'client_id'     => $client_id,
                'day'           => $day,
                'slot'          => $slots[$i],
                'start_time'    => $start_times[$i],
                'end_time'      => $end_times[$i],
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Slots saved successfully']);
    }


    public function distributorList(Request $request) {
    
        $slotDates = SlotBooking::orderBy('id', 'desc')->pluck('slot_date')->unique();
        // $query = SlotBooking::query();
        $availableDates = SlotBooking::pluck('slot_date')->unique()
                                        ->map(fn($date)=>Carbon::parse($date)->format('Y-m-d'))
                                        ->values()
                                        ->toArray();
        //dd($availableDates);
        $clients = User::whereHas('slotbooking')->orderBy('name')->get();
        $query = SlotBooking::with('user');

        if ($request->filled('slot_date')) {
            $query->whereDate('slot_date', $request->slot_date);
        }

        if($request->filled('client_id')) {
            $query->whereIn('client_id', $request->client_id);
        }

        //filter by client keyword
        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('distributor_name', 'like', "%{$keyword}%")
                        ->orWhere('distributor_address', 'like', "%{$keyword}%");
            });
        }

        $distributor = $query->orderBy('id', 'desc')->paginate(15);
        //dd($distributor);
        return view('admin.distributor.list', compact('distributor', 'slotDates', 'clients', 'availableDates'));
    }

    public function siteReady($id) {
        $site = SlotBooking::findOrFail($id);
        $site->site_ready = $site->site_ready ? 0 : 1;
        $site->save();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status changed for site ready'
        ]);
    }
    
    public function savesiteReadyRemarks(Request $request){
        $request->validate([
            'remarks'   => 'nullable|string|max:1000',
        ]);
        $slot = SlotBooking::findOrFail($request->id);
        $slot->remarks = $request->remarks;
        $slot->save();

        return redirect()->route('admin.slot-booking.distributorList')->with('success', 'Site ready Remarks added successfully');
    }

    public function completeStatus(Request $request, $id)
    {
        $booking = SlotBooking::findOrFail($id);

        // If both site_ready and training_done are done → force success
        if ($booking->site_ready == 1 && $booking->training_done == 1) {
            $booking->complete_status = 'success';
        } else {
            // Otherwise, use requested status
            $booking->complete_status = $request->status;
        }

        $booking->save();

        return response()->json([
            'status'  => 200,
            'message' => 'Status changed'
        ]);
    }


    public function trainingDone($id) {
        $training = SlotBooking::findOrFail($id);
        $training->training_done = $training->training_done ? 0 : 1;
        $training->save();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status changed for training done'
        ]);
    }

    public function savetrainingRemarks(Request $request) {
        //dd($request->all());
        $request->validate([
            'training_remarks'  => 'nullable|string|max:1000',
        ]);
            $training = SlotBooking::findOrFail($request->id);
            $training->training_remarks = $request->training_remarks;
            $training->save();

            return redirect()->route('admin.slot-booking.distributorList')->with('success', 'Training remarks added successfully');    
    }

    // public function exportDistList(Request $request) {

    //     $query = SlotBooking::with('user');

    //     // Filter by slot_date
    //     if ($request->filled('slot_date')) {
    //         $query->whereDate('slot_date', $request->slot_date);
    //     }

    //     // Filter by client_id
    //     if ($request->filled('client_id')) {
    //         $query->whereIn('client_id', $request->client_id);
    //     }
       
    //     // Filter by keyword 
    //     if($request->filled('keyword')) {
    //         $keyword = $request->keyword;
    //         $query->where(function($q) use ($keyword) {
    //             $q->whereHas('user', function($sub) use ($keyword) {
    //                 $sub->where('name', 'like', "%{$keyword}%");
    //             })->orWhere('distributor_name', 'like', "%{$keyword}%")
    //                 ->orWhere('distributor_address', 'like', "%{$keyword}%")
    //                 ->orWhere('distributor_contact_no', 'like', "%{$keyword}%")
    //                 ->orWhere('distributor_email', 'like', "%{$keyword}%");
    //         });
    //     }

    //     $distributors = $query->orderBy('id', 'desc')->get();

    //     if ($distributors->count() > 0) {
    //         $delimiter = ",";
    //         $filename = "distributor_export_" . date('Y-m-d') . ".csv";

    //         $f = fopen('php://memory', 'w');

    //         // CSV column headers
    //         $headers = ['Client Name', 'Distributor Name', 'Distributor Address', 'Contact Number', 
    //         'Email', 'Slot Date', 'Training complete status'];
    //         fputcsv($f, $headers, $delimiter);

    //         foreach ($distributors as $distributor) {
    //             $status = ($distributor->site_ready == 1 && $distributor->training_done == 1) ? 'SUCCESS' : 'FAILED';
    //             $lineData = [
    //                 $distributor->user ? $distributor->user->name : 'N/A',
    //                 $distributor->distributor_name,
    //                 $distributor->distributor_address,
    //                 $distributor->distributor_contact_no,
    //                 $distributor->distributor_email,
    //                 Carbon::parse($distributor->slot_date)->format('d-m-Y'),
    //                 $status,
    //             ];
    //             fputcsv($f, $lineData, $delimiter);
    //         }

    //         // Rewind and output
    //         fseek($f, 0);
    //         header('Content-Type: text/csv');
    //         header('Content-Disposition: attachment; filename="' . $filename . '";');
    //         fpassthru($f);
    //         exit;
    //     } else {
    //         return redirect()->back()->with('error', 'No records found to export.');
    //     }
    // }

    public function exportDistList(Request $request)
    {
        $query = SlotBooking::with('user');

        // Filter by slot_date
        if ($request->filled('slot_date')) {
            $query->whereDate('slot_date', $request->slot_date);
        }

        // Filter by client_id
        if ($request->filled('client_id')) {
            $query->whereIn('client_id', $request->client_id);
        }

        // Filter by keyword 
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('user', function ($sub) use ($keyword) {
                    $sub->where('name', 'like', "%{$keyword}%");
                })->orWhere('distributor_name', 'like', "%{$keyword}%")
                ->orWhere('distributor_address', 'like', "%{$keyword}%")
                ->orWhere('distributor_contact_no', 'like', "%{$keyword}%")
                ->orWhere('distributor_email', 'like', "%{$keyword}%");
            });
        }

        $distributors = $query->orderBy('id', 'desc')->get();

        if ($distributors->count() > 0) {
            $delimiter = ",";
            $filename = "distributor_export_" . date('Y-m-d') . ".csv";

            $f = fopen('php://memory', 'w');

            // CSV column headers (all fields from distributor_name → slot_end_time + complete_status)
            $headers = [
                'Client Name',
                'Distributor Name',
                'Distributor Code',
                'Distributor Address',
                'Contact Number',
                'Email',
                'City',
                'State',
                'Zone',
                'GST Number',
                'PAN Number',
                'Distributor Contact Person',
                'Distributor Contact Person Phone',
                'SO Name',
                'SO Contact',
                'Remarks',
                'Training Remarks',
                'Slot Date',
                'Slot Start Time',
                'Slot End Time',
                'Complete Status'
            ];
            fputcsv($f, $headers, $delimiter);

            foreach ($distributors as $distributor) {
                $lineData = [
                    $distributor->user ? $distributor->user->name : 'N/A',
                    $distributor->distributor_name,
                    $distributor->distributor_code,
                    $distributor->distributor_address,
                    $distributor->distributor_contact_no,
                    $distributor->distributor_email,
                    $distributor->city,
                    $distributor->state,
                    $distributor->zone,
                    $distributor->gst_number,
                    $distributor->pan_number,
                    $distributor->distributor_contact_person,
                    $distributor->distributor_contact_person_phone,
                    $distributor->so_name,
                    $distributor->so_contact_no,
                    $distributor->remarks,
                    $distributor->training_remarks,
                    Carbon::parse($distributor->slot_date)->format('d-m-Y'),
                    Carbon::parse($distributor->slot_start_time)->format('h:i A'),
                    Carbon::parse($distributor->slot_end_time)->format('h:i A'),
                    strtoupper($distributor->complete_status), // use DB value instead of calculated
                ];
                fputcsv($f, $lineData, $delimiter);
            }

            // Rewind and output
            fseek($f, 0);
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            fpassthru($f);
            exit;
        } else {
            return redirect()->back()->with('error', 'No records found to export.');
        }
    }


    public function siteReadinessForm($id) {
        $slot = SlotBooking::with('siteReadinessForm')->findOrFail($id);
        //dd($slot);
        $siteReady = $slot->siteReadinessForm;
        return view('admin.distributor.siteReadinessForm', compact('slot', 'siteReady'));
    }

    public function storeSiteReadiness(Request $request){
        //dd($request->all());
        $request->validate([
            'slot_booking_id'   => 'required|exists:slot_bookings,id'
        ]);

        $siteReady = SiteReadinessForm::updateOrCreate(
            ['slot_booking_id' => $request->slot_booking_id],
            [
                //  Input fields
                'distributor_code' => $request->distributor_code ?? null,
                'distributor_name' => $request->distributor_name ?? null,
                'full_address'     => $request->full_address ?? null,
                'office_phone_no'  => $request->office_phone_no ?? null,
                'city'             => $request->city ?? null,
                'state'            => $request->state ?? null,
                'zone'             => $request->zone ?? null,
                'contact_person'   => $request->contact_person ?? null,
                'distributor_email'=> $request->distributor_email ?? null,
                'contact_person_phone' => $request->contact_person_phone ?? null,
                'gst_number'       => $request->gst_number ?? null,
                'pan_number'       => $request->pan_number ?? null,
                'so_name'          => $request->so_name ?? null,
                'so_contact_number'=> $request->so_contact_number ?? null,
                'brands'           => $request->brands ?? null,
                'beat_name'        => $request->beat_name ?? null,
                'beat_id'          => $request->beat_id ?? null,
                'beat_type'        => $request->beat_type ?? null,
                'region_code'      => $request->region_code ?? null,
                'region_csp'       => $request->region_csp ?? null,
                'region_name'      => $request->region_name ?? null,
                'beat_distributor_codes' => $request->beat_distributor_codes ?? null,
                'employee_id'      => $request->employee_id ?? null,
                'employee_label'   => $request->employee_label ?? null,
                'employee_name'    => $request->employee_name ?? null,
                'designation_code' => $request->designation_code ?? null,
                'rm_employee_id'   => $request->rm_employee_id ?? null,
                'rm_designation_code' => $request->rm_designation_code ?? null,
                'state_code'       => $request->state_code ?? null,
                'employee_distributor_codes' => $request->employee_distributor_codes ?? null,
                'employee_distributor_mapping' => $request->employee_distributor_mapping ?? null,
                'dsr_distributor_mapping' => $request->dsr_distributor_mapping ?? null,
                'beat_employee_mapping' => $request->beat_employee_mapping ?? null,
                'supplier_distributor_mapping' => $request->supplier_distributor_mapping ?? null,
                'outlet_sync_csp'  => $request->outlet_sync_csp ?? null,
                'outlet_lead_creation' => $request->outlet_lead_creation ?? null,
                'outlet_lead_approval' => $request->outlet_lead_approval ?? null,
                'regional_price'   => $request->regional_price ?? null,
                'opening_stock'    => $request->opening_stock ?? null,
                'grn_invoice'      => $request->grn_invoice ?? null,
                'sales_order'      => $request->sales_order ?? null,
                'opening_points'   => $request->opening_points ?? null,

                //  Remarks fields
                'distributor_code_remarks' => $request->distributor_code_remarks ?? null,
                'distributor_name_remarks' => $request->distributor_name_remarks ?? null,
                'full_address_remarks'     => $request->full_address_remarks ?? null,
                'office_phone_no_remarks'  => $request->office_phone_no_remarks ?? null,
                'city_remarks'             => $request->city_remarks ?? null,
                'state_remarks'            => $request->state_remarks ?? null,
                'zone_remarks'             => $request->zone_remarks ?? null,
                'contact_person_remarks'   => $request->contact_person_remarks ?? null,
                'distributor_email_remarks'=> $request->distributor_email_remarks ?? null,
                'contact_person_phone_remarks' => $request->contact_person_phone_remarks ?? null,
                'gst_number_remarks'       => $request->gst_number_remarks ?? null,
                'pan_number_remarks'       => $request->pan_number_remarks ?? null,
                'so_name_remarks'          => $request->so_name_remarks ?? null,
                'so_contact_number_remarks'=> $request->so_contact_number_remarks ?? null,
                'brands_remarks'           => $request->brands_remarks ?? null,
                'beat_name_remarks'        => $request->beat_name_remarks ?? null,
                'beat_id_remarks'          => $request->beat_id_remarks ?? null,
                'beat_type_remarks'        => $request->beat_type_remarks ?? null,
                'region_code_remarks'      => $request->region_code_remarks ?? null,
                'region_csp_remarks'       => $request->region_csp_remarks ?? null,
                'region_name_remarks'      => $request->region_name_remarks ?? null,
                'beat_distributor_codes_remarks' => $request->beat_distributor_codes_remarks ?? null,
                'employee_id_remarks'      => $request->employee_id_remarks ?? null,
                'employee_label_remarks'   => $request->employee_label_remarks ?? null,
                'employee_name_remarks'    => $request->employee_name_remarks ?? null, 
                'designation_code_remarks' => $request->designation_code_remarks ?? null,
                'rm_employee_id_remarks'   => $request->rm_employee_id_remarks ?? null,
                'rm_designation_code_remarks' => $request->rm_designation_code_remarks ?? null,
                'state_code_remarks'       => $request->state_code_remarks ?? null,
                'employee_distributor_codes_remarks' => $request->employee_distributor_codes_remarks ?? null,
                'employee_distributor_mapping_remarks' => $request->employee_distributor_mapping_remarks ?? null,
                'dsr_distributor_mapping_remarks' => $request->dsr_distributor_mapping_remarks ?? null,
                'beat_employee_mapping_remarks' => $request->beat_employee_mapping_remarks ?? null,
                'supplier_distributor_mapping_remarks' => $request->supplier_distributor_mapping_remarks ?? null,
                'outlet_sync_csp_remarks'  => $request->outlet_sync_csp_remarks ?? null,
                'outlet_lead_creation_remarks' => $request->outlet_lead_creation_remarks ?? null,
                'outlet_lead_approval_remarks' => $request->outlet_lead_approval_remarks ?? null,
                'regional_price_remarks'   => $request->regional_price_remarks ?? null,
                'opening_stock_remarks'    => $request->opening_stock_remarks ?? null,
                'grn_invoice_remarks'      => $request->grn_invoice_remarks ?? null,
                'sales_order_remarks'      => $request->sales_order_remarks ?? null,
                'opening_points_remarks'   => $request->opening_points_remarks ?? null,
                //'remarks'                  => $request->remarks, // general remarks
            ]);
         return redirect()->back()->with('sucess', 'Form data saved successfully');   
    }

    public function siteStatus($slot_booking_id, $field, Request $request){
       try{
            // Find or create the SiteReadinessForm record
            $form = SiteReadinessForm::firstOrCreate(
                ['slot_booking_id'  => $slot_booking_id]
            );
            $status = $request->input('status');
            $form->update([$field => $status]);

            return response()->json([
                'status'    => 200,
                // 'message'   => ucfirst(str_replace('_', ' ', $field)) . 
                //                         ($status ? ' enabled successfully' : ' disabled successfully'),
                'message'   => 'Update successfully',
        ]);
       } catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'message'   => 'Something went wrong: ' . $e->getMessage(),
            ]);
       }
    }



    public function refreshFailedSlots(Request $request)
    {
        try {
            $today = Carbon::today();

            $slots = SlotBooking::whereDate('slot_date', '<', $today)
            ->where(function($q){
                $q->where('site_ready', 0)
                  ->orWhere('training_done', 0);
            })
            ->where('complete_status', '<>', 'failed')
            ->get();
            // $updatedCount = SlotBooking::where('site_ready', 0)->update(['site_ready' => 1]);

             $updatedCount = $slots->count();

            if($updatedCount > 0){
               SlotBooking::whereIn('id', $slots->pluck('id')->toArray())
                    ->update(['complete_status' => 'failed']);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Incomplete slots refreshed successfully!',
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
}
