<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
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
   public function index(Request $request)
{
    $keyword = $request->input('keyword');
    $type = $request->input('patient_type');

    $query = Customer::with('user');

    if ($keyword) {
    $query->where(function ($q) use ($keyword) {
        $q->where('ICNumber', 'like', "%{$keyword}%")
          ->orWhereHas('user', function ($u) use ($keyword) {
              $u->where('name', 'like', "%{$keyword}%");
          });
    });
}

    if (!empty($type)) {
        $query->where('category', $type);
    }

    $patientrecord = $query->get(); 
    log::info($patientrecord);
    if ($request->ajax()) {
        return view('Staff.partials.patient_table_rows', compact('patientrecord'));
    }
    return view('Staff.patientmanagement', compact('patientrecord'));
}

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(string $id)
{
    $patient = Customer::where('user_id', $id)->firstOrFail();
    Log::info($patient);
    $patientrecord = PatientRecord::where('customer_id', $patient->id)->get();
    $appointmentIds = $patientrecord->pluck('appointment_id');
    $appointments = Appointment::whereIn('id', $appointmentIds)
        ->latest()
        ->get();
    $services = Service::all();
    Log::info($appointments);
    return view('Staff.viewrecord', compact('patientrecord', 'patient', 'appointments', 'services')
    );
}

  public function edit(string $id)
{
    $patient = Customer::with('user')->findOrFail($id);
    $patientrecord = PatientRecord::where('customer_id', $patient->id)->get();
    $appointmentIds = $patientrecord->pluck('appointment_id');
    $selectedReasons = $patientrecord->flatMap(fn($r) => is_string($r->diagnosis) ? json_decode($r->diagnosis, true) ?? [] : ($r->diagnosis ?? []))->unique()->toArray();
    $selectedInjuries = $patientrecord->flatMap(fn($r) => is_string($r->type_of_injury) ? json_decode($r->type_of_injury, true) ?? [] : ($r->type_of_injury ?? []))->unique()->toArray();

    $services = Service::all();
    log::info($selectedInjuries);
    log::info($selectedReasons);
    return view('Staff.updaterecord', compact('patientrecord','patient','services','selectedReasons','selectedInjuries'));
}


  public function update(Request $request, string $customerId, string $appid)
{
    $customer = Customer::with('user')->findOrFail($customerId);
    $record = PatientRecord::where('appointment_id', $appid)->firstOrFail();
    $placeOfInjury = is_array($request->place_of_injury)? implode(',', array_map(fn ($v) => trim($v, " ,"), $request->place_of_injury)): trim($request->place_of_injury ?? '', " ,");
    $typeInjury = $request->type_injury ?? [];
    if (is_string($typeInjury)) {
        $decoded = json_decode($typeInjury, true);
        $typeInjury = is_array($decoded) ? $decoded : [$typeInjury];
    }
    $record->type_of_injury = json_encode($typeInjury);
    $treatment = $request->treatment ?? [];
    if (is_string($treatment)) {
        $decoded = json_decode($treatment, true);
        $treatment = is_array($decoded) ? $decoded : [$treatment];
    }
    $record->treatment = json_encode($treatment);
    log::info($record);
    log::info($placeOfInjury);
    $record->treatment = $treatment;
    $record->type_of_injury = $typeInjury;
    $record->place_of_injury = $placeOfInjury;
    $record->notes = $request->treatment_notes;
    $record->save();
    $appointment = Appointment::findOrFail($appid);
    $appointment->status = 'completed';
    $appointment->save();
    log::info('Updated patient record', $record->toArray());

    return $this->show($customer->user->id)->with('success', 'Treatment updated successfully.');
}

    public function destroy(string $id)
    {
        Patient::destroy($id);
        return view('Staff.patientmanagement');

    }
}

