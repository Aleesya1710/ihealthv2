<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Patient $patient)
    {
    }

    public function edit(Patient $patient)
    {
    }

    public function update(Request $request, string $id)
    {
        Log::info($request);
        log::info($id);

          $patient = Patient::findOrFail($id);

    $patient->update([
        'name' => $request->name,
        'ic_number' => $request->ic_number,
        'age' => $request->age,
        'gender' => $request->gender,
        'contact_number' => $request->contact_number,
        'patient_type' => $request->patient_type,
        'place_of_injury' => $request->place_of_injury,
        'reason_of_visit' => json_encode($request->reason_visit), 
        'type_of_injury' => json_encode($request->type_injury),
    ]);
    
    return redirect()->route('patientRecord',$patient->user_id)->with('success', 'Patient updated successfully!');
    }

    public function destroy(Patient $patient)
    {
    }
}
