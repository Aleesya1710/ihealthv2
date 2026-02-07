<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Models\Service;

use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        //$activeServices = Service::where('is_active', 1)->get();
        Log::info($services);
        return view('Customer.booking', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $services = Service::findOrFail($id);
        $unavailableDates = Holiday::pluck('date')->toArray(); 

        return view('Customer.createbooking', compact('services','unavailableDates'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
