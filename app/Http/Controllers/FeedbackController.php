<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
    }

    public function store(Request $request)
{
    Log::info($request);
     
    Feedback::create([
        'patient_id' => $request->user_id,
        'appointment_id' => $request->appointment_id,
        'rating' => $request->rating,
        'message' => $request->feedback,
    ]);

    return redirect()->back()->with('success', 'Feedback submitted!');
}


    public function show(Feedback $feedback)
    {
    }

    public function edit(Feedback $feedback)
    {
    }

    public function update(Request $request, Feedback $feedback)
    {
    }

    public function destroy(Feedback $feedback)
    {
    }
}
