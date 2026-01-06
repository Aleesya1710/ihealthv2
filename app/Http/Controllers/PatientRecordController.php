<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\patientRecord;
use App\Models\Service;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;

class PatientRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $keyword = $request->input('keyword');
    $type = $request->input('patient_type');

    $query = Patient::query();

    if (!empty($keyword)) {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%$keyword%")
              ->orWhere('ic_number', 'like', "%$keyword%");
        });
    }

    if (!empty($type)) {
        $query->where('patient_type', $type);
    }

    $patientrecord = $query->get(); 

    return view('Staff.patientmanagement', compact('patientrecord'));
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
    public function show(string $id)
    {
        log::info("sint");
        log::info($id);
        $patient = Patient::where('user_id', $id)->first();
        log::info($patient);
        $patientrecord = PatientRecord::where('patient_id', $patient->id)->get(); 
        log::info("sint");
        $appointments = Appointment::where('patient_id', $patient->id)->latest()->get();
        $services = Service::all();
        log::info("sint");
        log::info($appointments);
        
        return view('Staff.viewrecord', compact('patientrecord','patient','appointments','services'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        log::info("a");
        $patient = Patient::findOrFail($id);
        $patientrecord = PatientRecord::where('patient_id', $patient->id)->get(); 
        $appointments = Appointment::where('patient_id', $patient->id)->latest()->get();
        $services = Service::all();
       
        return view('Staff.updaterecord', compact('patientrecord','patient','appointments','services'));

    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, string $appid)
    {
        log::info($request);
        log::info($id);
        log::info("b");
        $id = Patient::findOrFail($id)->user_id;
        $record = PatientRecord::where('appointment_id',$appid)->first();
        $goals = is_array($request->treatment_goals) ? json_encode($request->treatment_goals) : null;
        $record->treatment = $goals;
        $record->notes = $request->treatment_notes;
        $record->save();
        
        $appointment = Appointment::findOrFail($appid);
        $appointment->status = 'completed'; 
        $appointment->save();
        log::info("c");
        return $this->show($id)->with('success', 'Treatment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Patient::destroy($id);
        return view('Staff.patientmanagement');

    }
    public function reportPreview($id)
    {
        $patient = Patient::findOrFail($id);
        $appointments = Appointment::where('patient_id', $id)->with('service')->get();
        $patientrecord = PatientRecord::where('patient_id', $id)->get();
        $painLocation = $patient->place_of_injury;
        $isPDF = false;
        Browsershot::html(View::make('report.body_image', ['painLocation' => $painLocation])->render())
        ->windowSize(400, 600)
        ->setOption('args', ['--no-sandbox'])
        ->save(public_path('image/body_image.png'));
        return view('report.report-preview', compact('patient', 'appointments', 'patientrecord','isPDF'));
    }

    public function generatePdf($id)
   {
        $patient = Patient::findOrFail($id);
        $appointments = Appointment::where('patient_id', $id)->with('service')->get();
        $patientrecord = PatientRecord::where('patient_id', $id)->get();
        $painLocation = $patient->place_of_injury;
        $isPDF = true;
        Browsershot::html(View::make('report.body_image', ['painLocation' => $painLocation])->render())
        ->windowSize(400, 600)
        ->setOption('args', ['--no-sandbox'])
        ->save(public_path('image/body_image.png'));
        $pdf = Pdf::loadView('report.patient_report', compact('patient', 'appointments', 'patientrecord','isPDF'));

    return $pdf->download('patient_report_'.$patient->name.'.pdf');
}
}

