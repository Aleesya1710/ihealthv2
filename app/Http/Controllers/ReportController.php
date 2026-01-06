<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Appointment; // if needed

class ReportController extends Controller
{
    public function download($userId)
    {
        $appointments = Appointment::where('user_id', $userId)->latest()->get();
        set_time_limit(300); 
        $pdf = Pdf::loadView('pdf.report', compact('appointments'));
        return $pdf->download('report.pdf');
    }
}
