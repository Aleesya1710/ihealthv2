<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\patientRecord;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use App\Notifications\AppointmentBooked;
use Dotenv\Store\File\Paths;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Holiday\MalaysiaHoliday;
class AppointmentController extends Controller
{
   

public function index(Request $request)
{
    log::info($request);
    $query = Appointment::with(['customer', 'service', 'staff','patientRecord']);

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
        $query->whereDate('date', $request->date); 
    }
    if ($request->filled('appointment_id')) {
        $query->where('id', $request->appointment_id);
    }

    $appointments = $query
        ->orderByRaw("CASE status WHEN 'upcoming' THEN 1 WHEN 'completed' THEN 2 WHEN 'cancelled' THEN 3 ELSE 4 END")
        ->orderBy('date', 'asc')
        ->orderBy('time', 'asc')
        ->get();
    $services = Service::all();
    $staff = Staff::with('user')->get();
    $customer = Customer::with('user')->get();
    log::info($staff);
    $holidayPlugin = new MalaysiaHoliday;
    $holidaysData = $holidayPlugin->fromState('Selangor')->get();
    $unavailableDates = [];
    if (isset($holidaysData['data'][0]['collection'][0]['data'])) {
        foreach ($holidaysData['data'][0]['collection'][0]['data'] as $item) {
            if (isset($item['is_holiday']) && $item['is_holiday'] === true) {
                $unavailableDates[] = Carbon::parse($item['date'])->format('Y-m-d');
            }
        }
    }
    if ($request->ajax()) {
        return view('Staff.partials.appointment_table_rows', compact('appointments'));
    }

    return view('Staff.appointmentManagement', compact('appointments', 'customer', 'services','staff','unavailableDates'));
}
       public function create($id)
        {
            $holidayPlugin = new MalaysiaHoliday;
            $holidaysData = $holidayPlugin->fromState('Selangor')->get(); 

            $holidays = [];
            if (isset($holidaysData['data'][0]['collection'][0]['data'])) {
                foreach ($holidaysData['data'][0]['collection'][0]['data'] as $item) {
                    if (isset($item['is_holiday']) && $item['is_holiday'] === true) {
                        $holidays[] = Carbon::parse($item['date'])->format('Y-m-d');
                    }
                }
            }
            Log::info('Holidays:', $holidays);
            $user = auth()->user();
            $patient = Customer::where('user_id', $user->id)->first();
            $services = Service::findOrFail($id);
            $staff = Staff::with("user")->get();
            return view('Customer.createbooking', compact('services', 'holidays', 'staff', 'patient'));
        }

    public function store(Request $request)
{
    Log::info($request->all());
    $staffid = null;
    $studentid = null;
    $staff_id = $request->service_id == '6' ? 3 : $request->staff_id;
    if($request->patietnt_type == 'staff') {$staffid = $request->student_id;}
    if($request->patietnt_type == 'student') {$studentid = $request->student_id;}
    DB::beginTransaction();
    try {
        if (!Customer::where('user_id', $request->patient_id)->exists()){
        Customer::create([
            'category' => $request->patient_type,
            'phoneNumber' => $request->phone_number,
            'ICNumber' => $request->ic_number,
            'studentID' => $studentid,
            'staffID' => $staffid,
            'user_id'   => $request->patient_id,
            'faculty' => $request->Faculty,
            'program' => $request->Program,
        ]);
    }
    $patientId = Customer::where('user_id', $request->patient_id)->first()->id;
        $appointment = Appointment::create([
            'staff_id'    => $staff_id,
            'date'        => $request->appointment_date,
            'time'        => $request->appointment_time,
            'status'      => 'upcoming',
            'service_id'  => $request->service_id,
        ]);

        log::info("lepas");
        $filePath = null;
        if ($request->hasFile('referral_letter')) {
            $filePath = $request->file('referral_letter')->store('referrals', 'public');
        }
        log::info($filePath);

       $record = PatientRecord::create([
            'customer_id'      => $patientId,
            'appointment_id'  => $appointment->id,
            'notes'           => $request->notes,
            'referral_letter' => $filePath,
]);

        DB::commit();
        $appointment->load(['service', 'staff.user', 'patientRecord.customer.user']);
        try {
            $customerUser = User::find($request->patient_id);
            if ($customerUser) {
                $customerUser->notify(new AppointmentBooked($appointment, 'customer'));
            }

            $staffUser = Staff::with('user')->where('staffID', $staff_id)->first()?->user;
            if ($staffUser) {
                $staffUser->notify(new AppointmentBooked($appointment, 'staff'));
            }
        } catch (\Exception $e) {
            Log::error('Error sending appointment notifications: ' . $e->getMessage());
        }
        return redirect()->route('Customer.booking', $request->service_id)
                         ->with('success', 'Appointment booked successfully.');
    
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error saving appointment or patient record: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while saving the appointment.');
    }
}




   public function update(Request $request, $id)
    {
        log::info($request);
        $appointment = Appointment::findOrFail($id);
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

        $appointment->update($data);

        return redirect()->route('appoinmentmanagement')->with('success', 'Appointment updated successfully!');
    }

  public function cancel(Appointment $appointment)
    {
        $appointment->status = 'cancelled';
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment cancelled successfully.');
    }

    public function destroy(Appointment $appointment)
    {
    }

    public function getSlots(Request $request)
    {
        $date = $request->input('date');

        $allSlots = $this->generateTimeSlots();
        $bookings = Appointment::where('date', $date)->get();

        $bookedSlots = $bookings->pluck('time')->map(function ($time) {
        return \Carbon\Carbon::parse($time)->format('H:i');
    })->unique()->values();

    $bookedStaff = $bookings->groupBy(function ($item) {
        return \Carbon\Carbon::parse($item->time)->format('H:i');
    })->map(function ($group) {
        return $group->pluck('staff_id')->values();
    });
        log::info($bookedSlots);
        log::info("hi");
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
  
    public function customerhistory(string $id){
    Log::info($id);
    $patient = Customer::where('user_id', $id)->first();
    if (!$patient) {
        $services = Service::all();
        $staff = Staff::with("User")->get();
        $holidayPlugin = new MalaysiaHoliday;
        $holidaysData = $holidayPlugin->fromState('Selangor')->get(); 
        $unavailableDates = [];
        if (isset($holidaysData['data'][0]['collection'][0]['data'])) {
            foreach ($holidaysData['data'][0]['collection'][0]['data'] as $item) {
                if (isset($item['is_holiday']) && $item['is_holiday'] === true) {
                    $unavailableDates[] = Carbon::parse($item['date'])->format('Y-m-d');
                }
            }
        }
        return view('Customer.bookinghistory', ['appointment' => collect([]),'services' => $services,'staff' => $staff,'patient' => null,'unavailableDates' => $unavailableDates
        ]);
    }
    $patientRecords = patientRecord::where('customer_id', $patient->id)->get();
    $appointmentIds = $patientRecords->pluck('appointment_id'); 
    $appointment = Appointment::with('feedback')->whereIn('id', $appointmentIds)->orderBy('date', 'desc')->get();
    $services = Service::all();
    $staff = Staff::with("User")->get();
    $holidayPlugin = new MalaysiaHoliday;
    $holidaysData = $holidayPlugin->fromState('Selangor')->get(); 
    $unavailableDates = [];
    if (isset($holidaysData['data'][0]['collection'][0]['data'])) {
        foreach ($holidaysData['data'][0]['collection'][0]['data'] as $item) {
            if (isset($item['is_holiday']) && $item['is_holiday'] === true) {
                $unavailableDates[] = Carbon::parse($item['date'])->format('Y-m-d');
            }
        }
    }
    return view('Customer.bookinghistory', compact('appointment', 'services', 'staff', 'patient', 'unavailableDates'));
}
    

public function reschedule(Request $request,string $id)
{
    log::info($id);
    log::info($request);

    $appointment = Appointment::find($id);
    $appointment->date = $request->appointment_date;
    $appointment->time = $request->appointment_time;
    if ($request->filled('status') && in_array($request->status, ['upcoming', 'completed'], true)) {
        $appointment->status = $request->status;
    }
    $appointment->save();
    log::info($appointment->save());
    return redirect()->back()->with('success', 'Appointment rescheduled successfully!');
}

}
