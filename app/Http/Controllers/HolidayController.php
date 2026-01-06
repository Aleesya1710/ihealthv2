<?php

namespace App\Http\Controllers;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Staff.holidaymanagement');
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
    Log::info('Holiday Create Request:', $request->all());

    $holiday = Holiday::create([
        'date' => $request->date,
        'name' => $request->reason,
    ]);

    return response()->json([
        'success' => true,
        'id' => $holiday->id, // Ensure frontend receives new ID
    ]);
}


    public function events()
    {
        $holidays = Holiday::all();
        $events = $holidays->map(function ($holiday) {
            return [
                'id' => $holiday->id,
                'name' => $holiday->name ?? 'Holiday',
                'date' => $holiday->date,
                'type' => 'full_day',
            ];
        });

        return response()->json($events);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $holiday = Holiday::findOrFail($id);

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $holiday->update(['name' => $request->reason]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        log::info($id);
        $holiday = Holiday::findOrFail($id);
        log::info($holiday);
        $holiday->delete();

        return response()->json(['success' => true]);
    }
}
