<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
     public function update(Request $request, string $id)
    {
        Log::info($request);
        $patient = Customer::with("User")->findOrFail($id);
        log::info($patient);
        $patient->update([
        'name' => $request->name,
        'ICNumber' => $request->ic_number,
        'phoneNumber' => $request->contact_number,
        'category' => $request->patient_type,
        'faculty' => $request->faculty,
        'program' => $request->program,

    ]);
    
    return redirect()->route('patientRecord',$patient->user_id)->with('success', 'Patient updated successfully!');
    }
}
