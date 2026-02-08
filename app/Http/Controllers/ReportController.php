<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Staff;
use App\Models\PatientRecord;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportController extends Controller
{
    public function index()
    {
        $staffMembers = Staff::with("user")
            ->where('position', 'Instructor')
            ->get();
        
        $services = Service::orderBy('name')->get();
        
        $patients = Customer::with("user")->get();
        
        $reportTitle = 'Reports';
        $reportSubtitle = 'Select filters to generate a report.';
        $reportType = null;
        $reportData = [
            'summary' => [
                'total' => 0,
                'completed' => 0,
                'pending' => 0,
                'cancelled' => 0,
            ],
            'appointments' => collect(),
        ];

        return view('report.report', compact('staffMembers', 'services', 'patients', 'reportTitle', 'reportSubtitle', 'reportType', 'reportData'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:staff,service,patient',
            'staff_id' => 'required_if:report_type,staff',
            'service_id' => 'required_if:report_type,service',
            'patient_id' => 'required_if:report_type,patient',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'fields' => 'nullable|array',
            'fields.*' => 'in:date,time,patient,service,staff,status,record',
        ]);

        $reportType = $request->report_type;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $selectedFields = $request->input('fields', ['date', 'time', 'patient', 'service', 'staff', 'status', 'record']);
        $isAllScope = (
            ($reportType === 'staff' && $request->staff_id === 'all') ||
            ($reportType === 'service' && $request->service_id === 'all') ||
            ($reportType === 'patient' && $request->patient_id === 'all')
        );

        $reportData = [];
        $reportTitle = '';
        $reportSubtitle = '';

        try {
            switch ($reportType) {
                case 'staff':
                    $reportData = $this->getStaffReport($request->staff_id, $dateFrom, $dateTo);
                    $reportTitle = "Staff Appointment Report";
                    if ($request->staff_id === 'all') {
                        $reportSubtitle = "Staff: All Staff";
                    } else {
                        $staff = Staff::with('user')->findOrFail($request->staff_id);
                        $reportSubtitle = "Staff: " . ($staff->user->name ?? 'Unknown');
                    }
                    break;

                case 'service':
                    $reportData = $this->getServiceReport($request->service_id, $dateFrom, $dateTo);
                    $reportTitle = "Service Report";
                    if ($request->service_id === 'all') {
                        $reportSubtitle = "Service: All Services";
                    } else {
                        $service = Service::findOrFail($request->service_id);
                        $reportSubtitle = "Service: {$service->name}";
                    }
                    break;

                case 'patient':
                    $reportData = $this->getPatientReport($request->patient_id, $dateFrom, $dateTo);
                    $reportTitle = "Patient Record Report";
                    if ($request->patient_id === 'all') {
                        $reportSubtitle = "Patient: All Patients";
                    } else {
                        $patient = Customer::with('user')->findOrFail($request->patient_id);
                        $reportSubtitle = "Patient: " . ($patient->user->name ?? 'Unknown');
                    }
                    break;
            }

            if ($dateFrom && $dateTo) {
                $reportSubtitle .= " | Period: " . date('d M Y', strtotime($dateFrom)) . " to " . date('d M Y', strtotime($dateTo));
            }

            return view('report.reportresult', compact('reportData', 'reportTitle', 'reportSubtitle', 'reportType', 'selectedFields', 'isAllScope'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }

    public function download(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:staff,service,patient',
            'staff_id' => 'required_if:report_type,staff',
            'service_id' => 'required_if:report_type,service',
            'patient_id' => 'required_if:report_type,patient',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'fields' => 'nullable|array',
            'fields.*' => 'in:date,time,patient,service,staff,status,record',
        ]);

        $reportType = $request->report_type;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $selectedFields = $request->input('fields', ['date', 'time', 'patient', 'service', 'staff', 'status', 'record']);
        $isAllScope = (
            ($reportType === 'staff' && $request->staff_id === 'all') ||
            ($reportType === 'service' && $request->service_id === 'all') ||
            ($reportType === 'patient' && $request->patient_id === 'all')
        );

        $reportData = [];
        $reportTitle = '';
        $reportSubtitle = '';

        try {
            switch ($reportType) {
                case 'staff':
                    $reportData = $this->getStaffReport($request->staff_id, $dateFrom, $dateTo);
                    $reportTitle = "Staff Appointment Report";
                    if ($request->staff_id === 'all') {
                        $reportSubtitle = "Staff: All Staff";
                        $filename = "staff_report_all_" . date('Ymd_His') . ".pdf";
                    } else {
                        $staff = Staff::with('user')->findOrFail($request->staff_id);
                        $reportSubtitle = "Staff: " . ($staff->user->name ?? 'Unknown');
                        $filename = "staff_report_{$staff->staffID}_" . date('Ymd_His') . ".pdf";
                    }
                    break;

                case 'service':
                    $reportData = $this->getServiceReport($request->service_id, $dateFrom, $dateTo);
                    $reportTitle = "Service Report";
                    if ($request->service_id === 'all') {
                        $reportSubtitle = "Service: All Services";
                        $filename = "service_report_all_" . date('Ymd_His') . ".pdf";
                    } else {
                        $service = Service::findOrFail($request->service_id);
                        $reportSubtitle = "Service: {$service->name}";
                        $filename = "service_report_{$service->id}_" . date('Ymd_His') . ".pdf";
                    }
                    break;

                case 'patient':
                    $reportData = $this->getPatientReport($request->patient_id, $dateFrom, $dateTo);
                    $reportTitle = "Patient Record Report";
                    if ($request->patient_id === 'all') {
                        $reportSubtitle = "Patient: All Patients";
                        $filename = "patient_report_all_" . date('Ymd_His') . ".pdf";
                    } else {
                        $patient = Customer::with('user')->findOrFail($request->patient_id);
                        $reportSubtitle = "Patient: " . ($patient->user->name ?? 'Unknown');
                        $filename = "patient_report_{$patient->id}_" . date('Ymd_His') . ".pdf";
                    }
                    break;
            }

            if ($dateFrom && $dateTo) {
            $reportSubtitle .= " | Period: " . date('d M Y', strtotime($dateFrom)) . " to " . date('d M Y', strtotime($dateTo));
        }

        $pdf = PDF::loadView('report.pdf', compact('reportData', 'reportTitle', 'reportSubtitle', 'reportType', 'selectedFields', 'isAllScope'));
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return back()->with('error', 'Error downloading report: ' . $e->getMessage());
        }
    }

    private function getStaffReport($staffId, $dateFrom = null, $dateTo = null)
    {
        $query = Appointment::with(['service', 'staff.user', 'patientRecord.customer.user'])
            ->when($staffId !== 'all', function ($query) use ($staffId) {
                $query->where('staff_id', $staffId);
            });

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $appointments = $query->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        $totalAppointments = $appointments->count();
        $completedAppointments = $appointments->where('status', 'completed')->count();
        $cancelledAppointments = $appointments->where('status', 'cancelled')->count();
        $upcomingAppointments = $appointments->where('status', 'upcoming')->count();
        
        $serviceBreakdown = $appointments->groupBy('service_id')->map(function ($group) {
            return [
                'service_name' => $group->first()->service->name ?? 'Unknown',
                'count' => $group->count()
            ];
        });

        return [
            'appointments' => $appointments,
            'summary' => [
                'total' => $totalAppointments,
                'completed' => $completedAppointments,
                'cancelled' => $cancelledAppointments,
                'upcoming' => $upcomingAppointments,
            ],
            'service_breakdown' => $serviceBreakdown
        ];
    }

    private function getServiceReport($serviceId, $dateFrom = null, $dateTo = null)
    {
        $query = Appointment::with(['service', 'staff.user', 'patientRecord.customer.user'])
            ->when($serviceId !== 'all', function ($query) use ($serviceId) {
                $query->where('service_id', $serviceId);
            });

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $appointments = $query->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        $totalAppointments = $appointments->count();
        $completedAppointments = $appointments->where('status', 'completed')->count();
        $cancelledAppointments = $appointments->where('status', 'cancelled')->count();
        $upcomingAppointments = $appointments->where('status', 'upcoming')->count();
        
        $staffBreakdown = $appointments->groupBy('staff_id')->map(function ($group) {
            return [
                'staff_name' => $group->first()->staff->user->name ?? 'Unknown',
                'count' => $group->count()
            ];
        });

        $monthlyBreakdown = $appointments->groupBy(function ($appointment) {
            return date('Y-m', strtotime($appointment->date));
        })->map(function ($group, $month) {
            return [
                'month' => date('F Y', strtotime($month . '-01')),
                'count' => $group->count()
            ];
        });

        return [
            'appointments' => $appointments,
            'summary' => [
                'total' => $totalAppointments,
                'completed' => $completedAppointments,
                'cancelled' => $cancelledAppointments,
                'upcoming' => $upcomingAppointments,
            ],
            'staff_breakdown' => $staffBreakdown,
            'monthly_breakdown' => $monthlyBreakdown
        ];
    }

    private function getPatientReport($patientId, $dateFrom = null, $dateTo = null)
    {
        $patient = null;
        $appointmentIds = collect();
        if ($patientId !== 'all') {
            $patient = Customer::with('user')->findOrFail($patientId);
            $appointmentIds = PatientRecord::where('customer_id', $patientId)->pluck('appointment_id');
        }

        $query = Appointment::with(['staff.user', 'service', 'patientRecord.customer.user'])
            ->when($patientId !== 'all', function ($query) use ($appointmentIds) {
                $query->whereIn('id', $appointmentIds);
            })
            ->when($patientId === 'all', function ($query) {
                $query->whereHas('patientRecord');
            });

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $appointments = $query->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        $totalAppointments = $appointments->count();
        $completedAppointments = $appointments->where('status', 'completed')->count();
        $cancelledAppointments = $appointments->where('status', 'cancelled')->count();
        $upcomingAppointments = $appointments->where('status', 'upcoming')->count();
        
        $serviceHistory = null;
        $recentVisits = collect();
        if ($patientId !== 'all') {
            $serviceHistory = $appointments->groupBy('service_id')->map(function ($group) {
                return [
                    'service_name' => $group->first()->service->name ?? 'Unknown',
                    'count' => $group->count(),
                    'last_visit' => $group->first()->date
                ];
            });

            $recentVisits = $appointments->where('status', 'completed')
                ->sortByDesc('date')
                ->take(5);
        }

        return [
            'patient' => $patientId !== 'all' ? $patient : null,
            'appointments' => $appointments,
            'summary' => [
                'total' => $totalAppointments,
                'completed' => $completedAppointments,
                'cancelled' => $cancelledAppointments,
                'upcoming' => $upcomingAppointments,
            ],
            'service_history' => $serviceHistory,
            'recent_visits' => $recentVisits
        ];
    }
}
