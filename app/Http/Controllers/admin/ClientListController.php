<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, SlotBooking};


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
        $slotDates = SlotBooking::select('slot_date') 
                                    ->distinct()
                                    ->orderBy('id', 'desc')
                                    ->pluck('slot_date');

        // $query = SlotBooking::query();
        $query = SlotBooking::with('user');

        if ($request->filled('slot_date')) {
            $query->whereDate('slot_date', $request->slot_date);
        }

        //filter by client keyword
        if($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('user', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('distributor_name', 'like', "%{$keyword}%");
            });
        }

        $distributor = $query->orderBy('id', 'desc')->paginate(20);
        return view('admin.distributor.list', compact('distributor', 'slotDates'));
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

    // public function trainingList(Request $request) {
    //     $slotDates = SlotBooking::select('slot_date') 
    //                                 ->distinct()
    //                                 ->orderBy('id', 'desc')
    //                                 ->pluck('slot_date');

    //     // $query = SlotBooking::query();
    //     $query = SlotBooking::with('user');

    //     if ($request->filled('slot_date')) {
    //         $query->whereDate('slot_date', $request->slot_date);
    //     }

    //     //filter by client keyword
    //     if($request->filled('keyword')) {
    //         $keyword = $request->keyword;
    //         $query->whereHas('user', function($q) use ($keyword) {
    //                 $q->where('name', 'like', "%{$keyword}%")
    //                     ->orWhere('distributor_name', 'like', "%{$keyword}%");
    //         });
    //     }

    //     $distributor = $query->orderBy('id', 'desc')->get();
    //     return view('admin.training.list', compact('distributor', 'slotDates'));
    // }

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

  
}
