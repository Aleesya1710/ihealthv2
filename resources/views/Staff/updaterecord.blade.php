@extends('layouts.layoutS')

@section('content')
@php
    $selectedReasons = json_decode($patient->reason_of_visit ?? '[]');
    $selectedInjuries = json_decode($patient->type_of_injury ?? '[]');
    $selectedPlace = $patient->place_of_injury ?? null;
@endphp

<div class="bg-gray-300 p-6 rounded-2xl max-w-5xl mx-auto shadow-md">
    <form method="POST" action="{{ route('patient.update', $patient->id) }}">
    @csrf
    @method('PUT') 
    {{-- Top Bar --}}
    <div class="flex justify-between mb-5">
        <div class="bg-white px-5 py-2 w-40 rounded-lg inline-block font-semibold">{{ $patient->id }}</div>
       <button type="submit"
            class="justify-center inline-block bg-[#104F5D] hover:bg-[#1d3a41] text-white font-semibold px-5 py-2 rounded-lg shadow transition duration-200">
            Save
        </button>
    </div>

    {{-- Patient Information --}}
    <div class="bg-white rounded-lg p-8 mb-4">
        <h2 class="text-center font-bold text-lg mb-3">PATIENT INFORMATION</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <label class="font-semibold">Name</label>
                <input type="text" name="name" value="{{ $patient->name }}" class="mt-1 block w-full border px-3 py-2 rounded bg-gray-100">
            </div>
            <div>
                <label class="font-semibold">IC Number</label>
                <input type="text" name="ic_number" value="{{ $patient->ic_number }}" class="mt-1 block w-full border px-3 py-2 rounded bg-gray-100">
            </div>
            <div>
                <label class="font-semibold">Age</label>
                <input type="number" name="age" value="{{ $patient->age }}" class="mt-1 block w-full border px-3 py-2 rounded bg-gray-100">
            </div>
            <div>
                <label class="font-semibold">Gender</label>
                <select name="gender" class="mt-1 block w-full border px-3 py-2 rounded bg-gray-100">
                    <option {{ $patient->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option {{ $patient->gender == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div>
                <label class="font-semibold">Contact Number</label>
                <input type="text" name="contact_number" value="{{ $patient->contact_number }}" class="mt-1 block w-full border px-3 py-2 rounded bg-gray-100">
            </div>
            <div>
                <label class="font-semibold">Patient Type</label>
                <select name="patient_type" class="mt-1 block w-full border px-3 py-2 rounded bg-gray-100">
                    <option {{ $patient->patient_type == 'student' ? 'selected' : '' }}>student</option>
                    <option {{ $patient->patient_type == 'fsr student' ? 'selected' : '' }}>fsr student</option>
                    <option {{ $patient->patient_type == 'uitm staff' ? 'selected' : '' }}>uitm staff</option>
                    <option {{ $patient->patient_type == 'public' ? 'selected' : '' }}>public</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Medical History --}}
    <div class="bg-white rounded-lg p-6 mb-6 shadow">
        <h2 class="text-center font-bold text-2xl text-gray-800 mb-6">Medical Information</h2>
        <input type="text" name="place_of_injury" id="place_of_injury" class="hidden">
        <div class="flex w-full h-auto gap-10">
            <div class="bg-white rounded-lg p-4 w-[50%] h-auto">
                <h2 class="text-center font-bold text-lg mb-3">PAIN ASSESSMENT</h2>
                <svg id="anatomy" viewBox="0 0 200 300" class="w-full h-[90%] bg-gray-100 rounded">
                   <!-- Head -->
                    <circle id="head" data-part="Head" cx="100" cy="40" r="30" fill="#E0E0E0" class="hover:fill-blue-300 cursor-pointer" />
                    <!-- Chest -->
                    <rect id="chest" data-part="Chest" x="70" y="80" width="60" height="80" fill="#E0E0E0" class="hover:fill-blue-300 cursor-pointer" />
                    <!-- Left Arm -->
                    <rect id="left-arm" data-part="Left Arm" x="30" y="80" width="30" height="100" fill="#E0E0E0" class="hover:fill-blue-300 cursor-pointer" />
                    <!-- Right Arm -->
                    <rect id="right-arm" data-part="Right Arm" x="140" y="80" width="30" height="100" fill="#E0E0E0" class="hover:fill-blue-300 cursor-pointer" />
                    <!-- Legs -->
                    <rect id="left-leg" data-part="Left Leg" x="70" y="180" width="25" height="100" fill="#E0E0E0" class="hover:fill-blue-300 cursor-pointer" />
                    <rect id="right-leg" data-part="Right Leg" x="105" y="180" width="25" height="100" fill="#E0E0E0" class="hover:fill-blue-300 cursor-pointer" />
                </svg>
            </div>
            <div class="mt-10 flex flex-col gap-10 item-center w-[50%]">
                <div >
                    <label class="block text-gray-700 font-semibold mb-1 ">Reason of Visit</label>
                    <select multiple
                        id="reason_visit"
                        name="reason_visit[]"
                        data-hs-select='{
                            "placeholder": "Select reason of visit",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-gray-100 border border-gray-300 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500",
                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-gray-100 border border-gray-300 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300",
                            "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-200 rounded-lg focus:outline-hidden focus:bg-gray-200",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                        }'
                        class="hidden">
                        <option value="">Choose</option>
                        <option value="injury_rehabilitation" {{ in_array('injury_rehabilitation', $selectedReasons ?? []) ? 'selected' : '' }}>Injury Rehabilitation</option>
                        <option value="post_surgery_recovery" {{ in_array('post_surgery_recovery', $selectedReasons ?? []) ? 'selected' : '' }}>Post-surgery Recovery</option>
                        <option value="chronic_condition_care" {{ in_array('chronic_condition_care', $selectedReasons ?? []) ? 'selected' : '' }}>Chronic Condition Care</option>
                        <option value="pain_management" {{ in_array('pain_management', $selectedReasons ?? []) ? 'selected' : '' }}>Pain Management</option>
                        <option value="sports_performance" {{ in_array('sports_performance', $selectedReasons ?? []) ? 'selected' : '' }}>Sports Performance Enhancement</option>
                        <option value="mobility_support" {{ in_array('mobility_support', $selectedReasons ?? []) ? 'selected' : '' }}>Mobility Support</option>
                        <option value="neurological_rehab" {{ in_array('neurological_rehab', $selectedReasons ?? []) ? 'selected' : '' }}>Neurological Rehabilitation</option>
                        <option value="physical_therapy" {{ in_array('physical_therapy', $selectedReasons ?? []) ? 'selected' : '' }}>Physical Therapy</option>
                        <option value="occupational_therapy" {{ in_array('occupational_therapy', $selectedReasons ?? []) ? 'selected' : '' }}>Occupational Therapy</option>
                        <option value="wellness_consultation" {{ in_array('wellness_consultation', $selectedReasons ?? []) ? 'selected' : '' }}>Wellness Consultation</option>

                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Type of Injury</label>
                    <select multiple
                        id="type_injury"
                        name="type_injury[]"
                        data-hs-select='{
                            "placeholder": "Select type of injury",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-gray-100 border border-gray-300 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500",
                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-gray-100 border border-gray-300 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300",
                            "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-200 rounded-lg focus:outline-hidden focus:bg-gray-200",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                        }'
                        class="hidden">
                        <option value="">Choose</option>
                        <option value="chronic_pain" {{ in_array('chronic_pain', $selectedInjuries ?? []) ? 'selected' : '' }}>Chronic Pain</option>
                        <option value="back_pain" {{ in_array('back_pain', $selectedInjuries ?? []) ? 'selected' : '' }}>Back Pain</option>
                        <option value="shoulder_injury" {{ in_array('shoulder_injury', $selectedInjuries ?? []) ? 'selected' : '' }}>Shoulder Injury</option>
                        <option value="acute_injury" {{ in_array('acute_injury', $selectedInjuries ?? []) ? 'selected' : '' }}>Acute Injury</option>
                        <option value="sports_injury" {{ in_array('sports_injury', $selectedInjuries ?? []) ? 'selected' : '' }}>Sports Injury</option>
                        <option value="neck_pain" {{ in_array('neck_pain', $selectedInjuries ?? []) ? 'selected' : '' }}>Neck Pain</option>
                        <option value="knee_injury" {{ in_array('knee_injury', $selectedInjuries ?? []) ? 'selected' : '' }}>Knee Injury</option>
                        <option value="fracture_recovery" {{ in_array('fracture_recovery', $selectedInjuries ?? []) ? 'selected' : '' }}>Fracture Recovery</option>
                        <option value="post_stroke" {{ in_array('post_stroke', $selectedInjuries ?? []) ? 'selected' : '' }}>Post-Stroke Rehabilitation</option>
                        <option value="muscle_strain" {{ in_array('muscle_strain', $selectedInjuries ?? []) ? 'selected' : '' }}>Muscle Strain</option>
                        <option value="nerve_damage" {{ in_array('nerve_damage', $selectedInjuries ?? []) ? 'selected' : '' }}>Nerve Damage</option>

                    </select>
                </div>
            </div>
        </div>    
    </div>
</div>
<script>
    document.querySelectorAll('#anatomy [data-part]').forEach(part => {
        part.addEventListener('click', function () {
            const selected = this.getAttribute('data-part');

            // Set value in the hidden input
            document.getElementById('place_of_injury').value = selected;

            // Optional: visually highlight the selected part
            document.querySelectorAll('#anatomy [data-part]').forEach(p => {
                p.setAttribute('fill', '#E0E0E0'); // Reset all
            });
            this.setAttribute('fill', '#60A5FA'); // blue-400
        });
    });

    const selectedPlace = @json($selectedPlace);
    const anatomyParts = document.querySelectorAll('#anatomy [data-part]');
    const inputPlace = document.getElementById('place_of_injury');

    anatomyParts.forEach(part => {
        part.addEventListener('click', function () {
            const selected = this.getAttribute('data-part');
            inputPlace.value = selected;

            anatomyParts.forEach(p => p.setAttribute('fill', '#E0E0E0'));
            this.setAttribute('fill', '#60A5FA'); // Highlight selected
        });

        // Pre-select from DB
        if (part.getAttribute('data-part') === selectedPlace) {
            part.setAttribute('fill', '#60A5FA');
            inputPlace.value = selectedPlace;
        }
    });
    
</script>

@endsection