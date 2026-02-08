<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {       
        $user = User::with('customer')->find(Auth::id());
        log::info($user);
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
{
   log::info($request);
    $user = $request->user();
    $user->fill($request->validated());

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();
    $user->load('customer');
    Log::info("debug");

    $existingCustomer = $user->customer;
    $category = $request->patient_type;

    $customerData = [
        'user_id' => $user->id,
        'category' => $category,
        'ICNumber' => $request->ic_number,
        'phoneNumber' => $request->contact_number,
        'faculty' => $request->input('faculty', $existingCustomer->faculty ?? ''),
        'program' => $request->input('program', $existingCustomer->program ?? ''),
        'studentID' => null,
        'staffID' => null,
    ];

    if ($category === 'student') {
        $customerData['studentID'] = $request->student_id;
    } elseif ($category === 'staff') {
        $customerData['staffID'] = $request->staff_id;
    }

    $user->customer()->updateOrCreate(
        ['user_id' => $user->id],
        $customerData
    );

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}


    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
