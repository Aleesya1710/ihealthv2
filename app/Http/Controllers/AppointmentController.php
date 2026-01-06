<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\Patient;
use App\Models\patientRecord;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use App\Notifications\AppointmentBooked;
use Dotenv\Store\File\Paths;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AppointmentController extends Controller
{
   

   public function index(Request $request)
{
    $query = Appointment::with(['user', 'service', 'staff','patient']);

    if ($request->filled('user_id')) {
        $query->where('patient_id', $request->user_id);
    }

    if ($request->filled('service_id')) {
        $query->where('service_id', $request->service_id);
    }

    if ($request->filled('staff_id')) {
        $query->where('staff_id', $request->staff_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
      if ($request->filled('date')) {
        $query->whereDate('date', $request->date);  // 👈 DATE filter
    }
    if ($request->filled('appointment_id')) {
        $query->where('id', $request->appointment_id);
    }

    $appointments = $query->get();
    $users = User::all();
    $services = Service::all();
    $staff = Staff::all();
    $unavailableDates = Holiday::pluck('date')->toArray(); 
    return view('Staff.appointmentManagement', compact('appointments', 'users', 'services','staff','unavailableDates'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $user = auth()->user();
        $patient = Patient::where('user_id', $user->id)->first();
        log::info($patient);
        $services = Service::findOrFail($id);
        $staff = Staff::all();
        $unavailableDates = Holiday::pluck('date')->toArray(); 
        return view('Customer.createbooking', compact('services','unavailableDates',"staff","patient"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    Log::info($request->all());
   
    $staff_id = $request->service_id == '6' ? 2 : $request->staff_id;

    DB::beginTransaction();
    try {
        if (!Patient::where('user_id', $request->patient_id)->exists()){
        Patient::create([
            'patient_type' => $request->patient_type,
            'contact_number' => $request->phone_number,
            'age' => $request->age,
            'name' => $request->patient_name,
            'ic_number' => $request->ic_number,
            'gender' => $request->gender,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'user_id'     => $request->patient_id,

        ]);
    }
    $patientId = Patient::where('user_id', $request->patient_id)->first()->id;
    log::info("patientId");
    log::info($patientId);
        $appointment = Appointment::create([
            'staff_id'    => $staff_id,
            'date'        => $request->appointment_date,
            'time'        => $request->appointment_time,
            'status'      => 'upcoming',
            'service_id'  => $request->service_id,
            'patient_id'  => $patientId,
        ]);

        log::info("lepas");
        $filePath = null;
        if ($request->hasFile('referral_letter')) {
            $filePath = $request->file('referral_letter')->store('referrals', 'public');
        }
        log::info($filePath);

       $record = PatientRecord::create([
    'patient_id'      => $patientId,
    'appointment_id'  => $appointment->id,
    'visit_date'      => $request->appointment_date,
    'notes'           => $request->notes,
    'referral_letter' => $filePath,
]);

        DB::commit();

        //email
        $user = User::find($request->patient_id);
        $staff = Staff::find($staff_id);

        $notification = new AppointmentBooked($appointment);

    if ($user) {
        $user->notify($notification);
    }

    if ($staff) {
        $staff->notify($notification);
    }

        return redirect()->route('Customer.booking', $request->service_id)
                         ->with('success', 'Appointment booked successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error saving appointment or patient record: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while saving the appointment.');
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id, $appointmentId)
    {
        log::info("sini");
        log::info($id);
        $patient = Patient::findOrFail($id);
        $appointment = Appointment::with('service')->where('id', $appointmentId)->where('patient_id', $id)->firstOrFail();

        return view('Staff.viewappointment', compact('patient', 'appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, $appointmentId)
{

    $appointment = Appointment::with('patient', 'service')
        ->where('id', $appointmentId)
        ->where('patient_id', $id)
        ->firstOrFail();
    $patientrecord = patientRecord::where('appointment_id', $appointmentId)->first();
     log::info($appointment);
    return view('Staff.viewappointment', compact('appointment','patientrecord'));
}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    log::info($request);
    $appointment = Appointment::findOrFail($id);
    $patientrecord = PatientRecord::where('appointment_id', $id)->first();
    $data = [];

    if ($request->filled('date')) {
        $data['date'] = $request->date;
    }

    if ($request->filled('time')) {
        $data['time'] = $request->time;
    }

    if ($request->filled('status')) {
        $data['status'] = $request->status;
    }

    if ($request->filled('service_id')) {
        $data['service_id'] = $request->service_id;
    }

    $appointment->update($data);
    if ($patientrecord) {
        $patientRecordData = [];

        if (isset($appointmentData['date'])) {
            $patientRecordData['visit_date'] = $appointmentData['date'];
        }

        if (isset($appointmentData['time'])) {
            $patientRecordData['time'] = $appointmentData['time'];
        }

        if (isset($appointmentData['status'])) {
            $patientRecordData['status'] = $appointmentData['status'];
        }

        if (isset($appointmentData['service_id'])) {
            $patientRecordData['service_id'] = $appointmentData['service_id'];
        }

        $patientrecord->update($patientRecordData);
    }

    return redirect()->route('appoinmentmanagement')->with('success', 'Appointment updated successfully!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }

    public function getSlots(Request $request)
    {
        $date = $request->input('date');

        $allSlots = $this->generateTimeSlots();
        $bookings = Appointment::where('date', $date)->where('status','upcoming')->get();

        $bookedSlots = $bookings->pluck('time')->map(function ($time) {
        return \Carbon\Carbon::parse($time)->format('H:i');
    })->unique()->values();

    $bookedStaff = $bookings->groupBy(function ($item) {
        return \Carbon\Carbon::parse($item->time)->format('H:i');
    })->map(function ($group) {
        return $group->pluck('staff_id')->values();
    });
        log::info($bookedSlots);
        log::info($bookedStaff);
        return response()->json([
            'allSlots' => $allSlots,
            'bookedSlots' => $bookedSlots,
            'bookedStaff' => $bookedStaff
        ]);
    }

    public function generateTimeSlots($start = '09:00', $end = '16:00', $interval = 60)
    {
        $slots = [];
        $startTime = strtotime($start);
        $endTime = strtotime($end);

        while ($startTime < $endTime) {
            $slots[] = date('H:i', $startTime);
            $startTime = strtotime("+$interval minutes", $startTime);
        }
        return $slots;
    }
    public function cancel(Appointment $appointment)
    {
        $appointment->status = 'cancelled';
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment cancelled successfully.');
    }
    public function customerhistory(string $id){
        log::info($id);
        $patient = Patient::where('user_id',$id)->first();
        $appointment = Appointment::with('feedback')->where('patient_id', $patient->id)->get();
        $services = Service::all();
        $staff = Staff::all();
        $patient = Patient::where('user_id',$id)->first();
        $unavailableDates = Holiday::pluck('date')->toArray(); 
        return view('Customer.bookinghistory', compact('appointment','services','staff','patient','unavailableDates'));
    }
    
    
 public function rescheduleForm(string $id)
{
    $appointment = Appointment::findOrFail($id);
    $staff = Staff::all();
    $patient = Patient::where('patient_id',$appointment->patient_id)->get();
    $unavailableDates = Holiday::pluck('date')->toArray(); 
   return view('Customer.rescheduleform', compact('appointment','staff','patient','unavailableDates'));

}
public function reschedule(Request $request,string $id)
{
    log::info($id);
    log::info($request);

    $appointment = Appointment::find($id);
    $appointment->date = $request->date;
    $appointment->time = $request->time;
    $appointment->staff_id = $request->staff_id;
    $appointment->save();
    log::info($appointment->save());
    return redirect()->back()->with('success', 'Appointment rescheduled successfully!');
}

}
