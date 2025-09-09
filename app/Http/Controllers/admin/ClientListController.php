<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, SlotBooking, SiteReadinessForm};


class ClientListController extends Controller
{
    //
    public function index(Request $request){
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

        $client = $query->latest('id')->paginate(10);

        return view('admin.clients.index', compact('client'));
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
    
    public function saveRemarks(Request $request){
        $request->validate([
            'remarks'   => 'nullable|string|max:1000',
        ]);

        $slot = SlotBooking::findOrFail($request->id);
        $slot->remarks = $request->remarks;
        $slot->save();

        return redirect()->back()->with('sucess', 'Remarks added successfully');
    }


    public function trainingDone($id) {
        $training = SlotBooking::findOrFail($id);
        $training->training_done = $training->training_done ? 0 : 1;
        $training->save();
        return response()->json([
            'status'    => 200,
            'message'   => 'Training status changed'
        ]);
    }

    public function saveRemarksTraining(Request $request) {
        //dd($request->all());
        $request->validate([
            'training_remarks'  => 'nullable|string|max:1000',
        ]);
            $training = SlotBooking::findOrFail($request->id);
            $training->training_remarks = $request->training_remarks;
            $training->save();

            return redirect()->route('admin.slot-booking.distributorList')->with('success', 'Training remarks added successfully');    
    }

    public function exportDistList(Request $request) {

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
        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->whereHas('user', function($sub) use ($keyword) {
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

            // CSV column headers
            $headers = ['Client Name', 'Distributor Name', 'Distributor Address', 'Contact Number', 
            'Email', 'Slot Date', 'Training complete status'];
            fputcsv($f, $headers, $delimiter);

            foreach ($distributors as $distributor) {
                $status = ($distributor->site_ready == 1 && $distributor->training_done == 1) ? 'SUCCESS' : 'FAILED';
                $lineData = [
                    $distributor->user ? $distributor->user->name : 'N/A',
                    $distributor->distributor_name,
                    $distributor->distributor_address,
                    $distributor->distributor_contact_no,
                    $distributor->distributor_email,
                    Carbon::parse($distributor->slot_date)->format('d-m-Y'),
                    $status,
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
        if(!$slot->siteReadinessForm) {
            $slot->siteReadinessForm()->create([
                'slot_booking_id'       => $slot->id,
                'distributor_code'      => $slot->distributor_code ?? null,
                'distributor_name'      => $slot->distributor_name ?? null,
                'full_address'          => $slot->full_address ?? null,
                'office_phone_no'       => $slot->office_phone_no ?? null,
                'city'                  => $slot->city ?? null,
                'state'                 => $slot->state ?? null,
                'zone'                  => $slot->zone ?? null,
                'contact_person'        => $slot->contact_person ?? null,
                'distributor_email'     => $slot->distributor_email ?? null,
                'contact_person_phone'  => $slot->contact_person_phone ?? null,
                'gst_number'            => $slot->gst_number ?? null,
                'pan_number'            => $slot->pan_number ?? null,
                'so_name'               => $slot->so_name ?? null,
                'so_contact_number'     => $slot->so_contact_number ?? null,
                'brands'                => $slot->brands ?? null,
                'beat_name'             => $slot->beat_name ?? null,
                'beat_id'               => $slot->beat_id ?? null,
                'beat_type'             => $slot->beat_type ?? null,
                'region_code'           => $slot->region_code ?? null,
                'region_csp'            => $slot->region_csp ?? null,
                'region_name'           => $slot->region_name ?? null,
                'beat_distributor_codes'      => $slot->beat_distributor_codes ?? null,
                'employee_id'           => $slot->employee_id ?? null,
                'employee_label'        => $slot->employee_label ?? null,
                'employee_name'         => $slot->employee_name ?? null,
                'designation_code'      => $slot->designation_code ?? null,
                'rm_employee_id'        => $slot->rm_employee_id ?? null,
                'rm_designation_code'   => $slot->rm_designation_code ?? null,
                'state_code'            => $slot->state_code ?? null,
                'employee_distributor_codes'   => $slot->employee_distributor_codes ?? null,
                'employee_distributor_mapping' => $slot->employee_distributor_mapping ?? null,
                'dsr_distributor_mapping'      => $slot->dsr_distributor_mapping ?? null,
                'beat_employee_mapping'        => $slot->beat_employee_mapping ?? null,
                'supplier_distributor_mapping' => $slot->supplier_distributor_mapping ?? null,
                'outlet_sync_csp'      => $slot->outlet_sync_csp ?? null,
                'outlet_lead_creation'         => $slot->outlet_lead_creation ?? null,
                'outlet_lead_approval'         => $slot->outlet_lead_approval ?? null,
                'regional_price'        => $slot->regional_price ?? null,
                'opening_stock'         => $slot->opening_stock ?? null,
                'grn_invoice'           => $slot->grn_invoice ?? null,
                'sales_order'           => $slot->sales_order ?? null,
                'opening_points'        => $slot->opening_points ?? null,
                'remarks'               => $slot->remarks ?? null,
                'status'                => $slot->status ?? null,

            ]);
        }
        $siteReady = $slot->siteReadinessForm;
        return view('admin.distributor.siteReadinessForm', compact('slot', 'siteReady'));
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
                'message'   => ucfirst(str_replace('_', ' ', $field)) . 
                                        ($status ? ' enabled successfully' : ' disabled successfully'),
        ]);
       } catch(\Exception $e) {
            return response()->json([
                'status'    => 500,
                'message'   => 'Something went wrong: ' . $e->getMessage(),
            ]);
       }
    }


}
