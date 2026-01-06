<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\patientRecord;
use App\Models\Appointment;
use App\Models\Holiday;
use App\Models\Service;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalAppointmentsToday = Appointment::whereDate('date', $today)->count();
        $AppointmentsToday = Appointment::with('service','staff','user.patient')->whereDate('date', $today)->orderBy('time', 'asc')->get();
        $totalPatients = Patient::count();
        $CancelledAppointment = Appointment::where('status','cancelled')->count();
        $UpcomingAppointment = Appointment::where('status','upcoming')->count();
        $CompletedAppointment = Appointment::where('status','completed')->whereDate('date', $today)->count();
       $totalAppointmentsByMonth = Appointment::selectRaw(
        'MONTH(date) as month_num, DATE_FORMAT(MIN(date), "%M") as month_name, COUNT(*) as total'
    )
    ->groupBy('month_num')
    ->orderBy('month_num')
    ->get();

        $totalAppointmentsByDate = Appointment::selectRaw('DATE(date) as appointment_date, COUNT(*) as total')->whereBetween('date', [$startOfMonth, $endOfMonth])->groupBy('appointment_date')->orderBy('appointment_date')->get();
        $upcomingAppointments = Appointment::with('service','staff',)->whereDate('date', '>=', $today)->orderBy('date')->get();
        $feedback = Feedback::with('user')->get();
        $feedbackByRating = Feedback::selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();
         return view('Staff.dashboardS', compact('totalAppointmentsToday','AppointmentsToday','totalPatients','CancelledAppointment','UpcomingAppointment','CompletedAppointment','totalAppointmentsByMonth','totalAppointmentsByDate','upcomingAppointments','feedback','feedbackByRating'));    
    }
}
