<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Models\Service;

use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        Log::info($services);
        return view('Customer.booking', compact('services'));
    }

    public function create($id)
    {
        $services = Service::findOrFail($id);
        $unavailableDates = Holiday::pluck('date')->toArray(); 

        return view('Customer.createbooking', compact('services','unavailableDates'));
    }

    public function store(Request $request)
    {
    }

    public function show(string $id)
    {
    }

    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}
