<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
