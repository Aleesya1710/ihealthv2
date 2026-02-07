@extends('layouts.layoutS')

@section('content')
@php
use Carbon\Carbon;
$age = '-';
$gender = '-';
$ic = str_replace('-', '', $patient->ICNumber ?? '');
if (!empty($ic) && strlen($ic) >= 6) {
    $birth = substr($ic, 0, 6);
    $year = substr($birth, 0, 2);
    $month = substr($birth, 2, 2);
    $day = substr($birth, 4, 2);
    if (is_numeric($year) && is_numeric($month) && is_numeric($day)) {
        $year = intval($year) > intval(date('y')) ? '19'.$year : '20'.$year;
        try {
            $birthDate = $year.'-'.$month.'-'.$day;
            $age = Carbon::parse($birthDate)->age;
$genderDigit = intval(substr($ic, -1, 1));
            $gender = ($genderDigit % 2 === 0) ? 'Female' : 'Male';
        } catch (\Exception $e) {
            $age = '-';
            $gender = '-';
        }
    }
}
@endphp

<div class="bg-gray-300 p-6 rounded-2xl max-w-5xl mx-auto shadow-md">
    <form method="POST" action="{{ route('patient.update', $patient->id) }}">
        @csrf
        @method('PUT')

        {{-- Top Bar --}}
        <div class="flex justify-between mb-5">
            <div class="bg-white px-5 py-2 w-40 rounded-lg inline-block font-semibold">{{ $patient->id }}</div>
            <button type="submit" class="justify-center inline-block bg-[#104F5D] hover:bg-[#1d3a41] text-white font-semibold px-5 py-2 rounded-lg shadow transition duration-200">
                Save
            </button>
        </div>

        {{-- Patient Information (Editable) --}}
        <div class="bg-white rounded-lg p-8 mb-4">
            <h2 class="text-center font-bold text-lg mb-3">PATIENT INFORMATION</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                {{-- Name --}}
                <div>
                    <label class="font-semibold">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ $patient->user->name ?? '' }}"
                        class="border px-3 py-1 my-4 bg-gray-100 rounded w-full"
                    >
                </div>

                {{-- IC Number --}}
                <div>
                    <label class="font-semibold">IC Number</label>
                    <input
                        type="text"
                        name="ic_number"
                        value="{{ $patient->ICNumber }}"
                        class="border px-3 py-1 my-4 bg-gray-100 rounded w-full"
                    >
                </div>

                {{-- Contact Number --}}
                <div>
                    <label class="font-semibold">Contact Number</label>
                    <input
                        type="text"
                        name="contact_number"
                        value="{{ $patient->phoneNumber }}"
                        class="border px-3 py-1 my-4 bg-gray-100 rounded w-full"
                    >
                </div>

                {{-- Faculty --}}
                <div>
                    <label class="font-semibold">Faculty</label>
                    <input
                        type="text"
                        name="faculty"
                        value="{{ $patient->faculty }}"
                        class="border px-3 py-1 my-4 bg-gray-100 rounded w-full"
                    >
                </div>

                {{-- Program --}}
                <div>
                    <label class="font-semibold">Program</label>
                    <input
                        type="text"
                        name="program"
                        value="{{ $patient->program ?? '' }}"
                        class="border px-3 py-1 my-4 bg-gray-100 rounded w-full"
                    >
                </div>

                {{-- Category --}}
                <div>
                    <label class="font-semibold">Category</label>
                    <select
                        name="patient_type"
                        class="border px-3 py-1 my-4 bg-gray-100 rounded w-full"
                    >
                        <option value="student" {{ $patient->category === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="uitm staff" {{ $patient->category === 'uitm staff' ? 'selected' : '' }}>UiTM Staff</option>
                        <option value="public" {{ $patient->category === 'public' ? 'selected' : '' }}>Public</option>
                    </select>
                </div>

            </div>
        </div>
    
@endsection
