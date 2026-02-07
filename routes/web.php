<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientRecordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DashboardController;
use App\Models\Appointment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboardS', function () {
    return route('dashboardS');
})->middleware(['auth', 'verified'])->name('dashboardStaff');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//customer
Route::get('/appointment', [ServiceController::class, 'index'])->name('Customer.booking');
Route::get('/appointment/{id}',[AppointmentController::class, 'create'])->name('Customer.createbooking');
Route::post('/appointment/store', [AppointmentController::class, 'store'])->name('appointment.store');
Route::get('/appointmenthistory/{id}', [AppointmentController::class, 'customerhistory'])->name('appointmenthistory');
Route::get('/get-slots', [AppointmentController::class, 'getSlots']);
Route::put('/appointment/{id}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointmentReschedule');
Route::patch('/appointment/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointmentCancel');
Route::post('/appointment/feedback', [FeedbackController::class, 'store'])->name('createfeedback');


//staff
Route::get('/dashboardS', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboardS');
Route::get('/appointmentmanagement', [AppointmentController::class, 'index'])->name('appoinmentmanagement');
Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointment.cancel');
Route::post('/appointments/{id}/update', [AppointmentController::class, 'update'])->name('appointment.update');
Route::get('/patientmanagement', [PatientRecordController::class, 'index'])->name('patientmanagement');
Route::get('/patientmanagement/{id}',[PatientRecordController::class, 'show'])->name('patientRecord');
Route::get('/patientmanagement/{id}/{appointmentId}', [AppointmentController::class, 'edit'])->name('editappointment');
Route::put('/patientmanagement/{id}/{appointmentId}/update', [PatientRecordController::class, 'update'])->name('updateappointment');
Route::patch('/appointments/{appointment}/destroy', [PatientRecordController::class, 'destroy'])->name('patient.delete');
Route::get('/patients/{id}/edit', [PatientRecordController::class, 'edit'])->name('patient.edit');
Route::put('/patients/{id}', [CustomerController::class, 'update'])->name('patient.update');
Route::get('/patient-report/{id}/preview', [PatientRecordController::class, 'reportPreview'])->name('patients.report.preview');
Route::get('/patient-report/{id}/generate-pdf', [PatientRecordController::class, 'generatePdf'])->name('patientrecord.report.download');


require __DIR__.'/auth.php';
