<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\{SlotBooking};

class TrainingController extends Controller
{
    //

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

            return redirect()->route('client.slot-booking.distributorList')->with('success', 'Training remarks added successfully');    
    }
}
