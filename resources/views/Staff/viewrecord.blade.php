@extends('layouts.layoutS')

@section('content')
<div class="bg-gray-300 p-6 rounded-2xl max-w-5xl mx-auto shadow-md">

    <div class="flex justify-between mb-5">
        <div class="bg-white px-5 py-2 w-40 rounded-lg inline-block font-semibold">
            {{ $patient->id }}
        </div>
        <div>
          <button type="button"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    onclick="openModal('updatePatientModal')">
                Update Record
            </button>
        </div>
    </div>
    <div class="bg-white rounded-lg p-8 mb-4">
        <h2 class="text-center font-bold text-lg mb-3">PATIENT INFORMATION</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div><label class="font-semibold">Name</label>
                <div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->user->name ?? '-' }}</div>
            </div>
            <div><label class="font-semibold">IC Number</label>
                <div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->ICNumber }}</div>
            </div>
            <div><label class="font-semibold">Contact Number</label>
                <div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->phoneNumber }}</div>
            </div>
            <div><label class="font-semibold">Faculty</label>
                <div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->faculty }}</div>
            </div>
            <div><label class="font-semibold">Program</label>
                <div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->program ?? '-' }}</div>
            </div>
            <div><label class="font-semibold">Category</label>
                <div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ ucfirst($patient->category) }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6 mb-6 shadow">
        <h2 class="text-center font-bold text-2xl mb-6">Medical Information</h2>
        @php
            $painLocation = $patientrecord->pluck('place_of_injury')
                ->filter()
                ->flatMap(function ($value) {
                    if (is_array($value)) {
                        return $value;
                    }
                    return array_filter(array_map('trim', explode(',', $value)));
                })
                ->filter()
                ->unique()
                ->values();
        @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-4">
                    <h2 class="text-center font-bold text-lg mb-3">PAIN ASSESSMENT</h2>
                    <svg id="anatomy" viewBox="0 0 200 300" class="w-full h-auto bg-gray-100 rounded">
                        <g class="stroke-gray-300" stroke-width="1">
                            <circle id="head" data-part="Head" cx="100" cy="44" r="24"
                                fill="{{ $painLocation->contains('Head') ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer transition-colors" />
                            <rect id="chest" data-part="Chest" x="68" y="76" width="64" height="100" rx="16"
                                fill="{{ $painLocation->contains('Chest') ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer transition-colors" />
                            <rect id="left-arm" data-part="Left Arm" x="40" y="84" width="22" height="106" rx="11"
                                fill="{{ $painLocation->contains('Left Arm') ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer transition-colors" />
                            <rect id="right-arm" data-part="Right Arm" x="138" y="84" width="22" height="106" rx="11"
                                fill="{{ $painLocation->contains('Right Arm') ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer transition-colors" />
                            <rect id="left-leg" data-part="Left Leg" x="78" y="180" width="20" height="110" rx="10"
                                fill="{{ $painLocation->contains('Left Leg') ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer transition-colors" />
                            <rect id="right-leg" data-part="Right Leg" x="102" y="180" width="20" height="110" rx="10"
                                fill="{{ $painLocation->contains('Right Leg') ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer transition-colors" />
                        </g>
                    </svg>
                </div>

        <div>
            <div class="m-4">
                <label class="font-semibold">Reason Of Visit</label>
                @php
                    $treatments = $patientrecord->pluck('diagnosis')->flatten()->filter()->unique();
                @endphp
                @if($treatments->count())
                    @foreach($treatments as $treatment)
                        <div class="px-4 py-2 bg-gray-100 rounded mb-3">
                            {{ ucwords(str_replace('_',' ', $treatment)) }}
                        </div>
                    @endforeach
                @else
                    -
                @endif
            </div>
            <div class="m-4">
                <label class="font-semibold mx-2">Type of Injury</label>
                @php
                    $types = $patientrecord->pluck('type_of_injury')->flatten()->filter()->unique();
                @endphp
                @if($types->count())
                    @foreach($types as $type)
                        <div class="px-4 py-2 bg-gray-100 rounded mb-3">
                            {{ ucwords(str_replace('_',' ', $type)) }}
                        </div>
                    @endforeach
                @else
                    -
                @endif
            </div>

            <div class="m-4">
                <label class="font-semibold">Treatment Plan</label>
                @php
                    $treatments = $patientrecord->pluck('treatment')->flatten()->filter()->unique();
                @endphp
                @if($treatments->count())
                    @foreach($treatments as $treatment)
                        <div class="px-4 py-2 bg-gray-100 rounded mb-3">
                            {{ ucwords(str_replace('_',' ', $treatment)) }}
                        </div>
                    @endforeach
                @else
                    -
                @endif
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-center font-bold text-lg mb-3">APPOINTMENT HISTORY</h2>

        @foreach ($appointments as $app)
            <div class="bg-white rounded-lg p-4 mb-4 shadow flex justify-between">
                <div class="grid grid-cols-2 gap-4 w-full">
                    <div><strong>ID:</strong> {{ $app->id }}</div>
                    <div><strong>Date:</strong> {{ $app->date }}</div>
                    <div><strong>Time:</strong> {{ $app->time }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($app->status) }}</div>
                    <div><strong>Service:</strong> {{ $app->service->name ?? '-' }}</div>
                </div>

                <a href="#"
                   data-modal-target="modal-{{ $app->id }}"
                   data-modal-toggle="modal-{{ $app->id }}"
                   class="flex items-center gap-2 text-blue-600 hover:text-blue-800">

                    @if($app->status === 'upcoming')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5l3 3L14 13H11v-3l7.5-7.5z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    @endif

                </a>
            </div>

            <div id="modal-{{ $app->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl w-full max-w-3xl max-h-[90vh] flex flex-col shadow-lg relative">
                    
                    <div class="flex justify-between items-center p-6 border-b">
                        <h2 class="text-2xl font-bold">Appointment Details</h2>
                        <button data-modal-hide="modal-{{ $app->id }}" class="text-gray-500 hover:text-gray-700 text-3xl leading-none">×</button>
                    </div>

                    <div class="overflow-y-auto p-6 flex-1">
                        @php
                            $record = $patientrecord->where('appointment_id', $app->id)->first();
                            $editable = $app->status === 'upcoming';
                            $recordPainLocation = is_array($record->place_of_injury) ? collect($record->place_of_injury) : collect(explode(',', $record->place_of_injury ?? ''));
                        @endphp

                        @if($editable)
                            <form method="POST" action="{{ route('updateappointment', ['id' => $patient->id, 'appointmentId' => $app->id]) }}">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="place_of_injury" id="place_of_injury_{{ $app->id }}" value="{{ is_array($record->place_of_injury) ? implode(',', $record->place_of_injury) : ($record->place_of_injury ?? '') }}">

                                <div class="space-y-4">
                                    <div class="bg-gray-50 rounded-lg p-6">
                                        <h3 class="text-center font-bold text-lg mb-4">APPOINTMENT INFORMATION</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="font-semibold block mb-2">Instructor</label>
                                                <div class="border px-3 py-2 bg-white rounded">{{ $app->staff->user->name ?? '-' }}</div>
                                            </div>
                                            <div>
                                                <label class="font-semibold block mb-2">Service</label>
                                                <div class="border px-3 py-2 bg-white rounded">{{ $app->service->name ?? '-' }}</div>
                                            </div>
                                            <div>
                                                <label class="font-semibold block mb-2">Date</label>
                                                <div class="border px-3 py-2 bg-white rounded">{{ $app->date }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-6">
                                        <h3 class="text-center font-bold text-lg mb-4">
                                            Appointment Medical Summary
                                        </h3>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div class="bg-white rounded-lg p-3">
                                                <h4 class="text-center font-semibold text-sm mb-2">PAIN ASSESSMENT</h4>
                                                <p class="text-xs text-center text-gray-500">Click on body parts to select</p>
                                                <div class="flex justify-center">
                                                    <svg id="anatomy-{{ $app->id }}" viewBox="0 0 200 300" style="width: 250px; height: 300px;" class="bg-white rounded">
                                                        <g stroke="#D1D5DB" stroke-width="2">
                                                            <circle data-part="Head" cx="100" cy="44" r="24"
                                                                fill="{{ $recordPainLocation->contains('Head') ? '#60A5FA' : '#E5E7EB' }}"
                                                                class="hover:fill-blue-300 cursor-pointer transition-colors" />
                                                            <rect data-part="Chest" x="68" y="76" width="64" height="100" rx="16"
                                                                fill="{{ $recordPainLocation->contains('Chest') ? '#60A5FA' : '#E5E7EB' }}"
                                                                class="hover:fill-blue-300 cursor-pointer transition-colors" />
                                                            <rect data-part="Left Arm" x="40" y="84" width="22" height="106" rx="11"
                                                                fill="{{ $recordPainLocation->contains('Left Arm') ? '#60A5FA' : '#E5E7EB' }}"
                                                                class="hover:fill-blue-300 cursor-pointer transition-colors" />
                                                            <rect data-part="Right Arm" x="138" y="84" width="22" height="106" rx="11"
                                                                fill="{{ $recordPainLocation->contains('Right Arm') ? '#60A5FA' : '#E5E7EB' }}"
                                                                class="hover:fill-blue-300 cursor-pointer transition-colors" />
                                                            <rect data-part="Left Leg" x="78" y="180" width="20" height="110" rx="10"
                                                                fill="{{ $recordPainLocation->contains('Left Leg') ? '#60A5FA' : '#E5E7EB' }}"
                                                                class="hover:fill-blue-300 cursor-pointer transition-colors" />
                                                            <rect data-part="Right Leg" x="102" y="180" width="20" height="110" rx="10"
                                                                fill="{{ $recordPainLocation->contains('Right Leg') ? '#60A5FA' : '#E5E7EB' }}"
                                                                class="hover:fill-blue-300 cursor-pointer transition-colors" />
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                            <div>
                                                <label class="block text-gray-700 font-semibold mb-1">Reason of Visit</label>
                                                <select multiple
                                                    id="reason_visit"
                                                    name="reason_visit[]"
                                                    data-hs-select='{
                                                        "placeholder": "Select reason of visit",
                                                        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                                        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-300 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500",
                                                        "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-300 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300",
                                                        "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-200 rounded-lg focus:outline-hidden focus:bg-gray-200",
                                                        "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                                                        "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                                    }' class="hidden">
                                                    <option value="injury_rehabilitation">Injury Rehabilitation</option>
                                                    <option value="post_surgery_recovery">Post-surgery Recovery</option>
                                                    <option value="chronic_condition_care">Chronic Condition Care</option>
                                                    <option value="pain_management">Pain Management</option>
                                                    <option value="sports_performance">Sports Performance Enhancement</option>
                                                    <option value="mobility_support">Mobility Support</option>
                                                    <option value="neurological_rehab">Neurological Rehabilitation</option>
                                                    <option value="physical_therapy">Physical Therapy</option>
                                                    <option value="occupational_therapy">Occupational Therapy</option>
                                                    <option value="wellness_consultation">Wellness Consultation</option>
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
                                                        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-300 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500",
                                                        "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-300 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300",
                                                        "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-200 rounded-lg focus:outline-hidden focus:bg-gray-200",
                                                        "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                                                        "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                                    }'class="hidden">
                                                    <option value="chronic_pain">Chronic Pain</option>
                                                    <option value="back_pain">Back Pain</option>
                                                    <option value="shoulder_injury">Shoulder Injury</option>
                                                    <option value="acute_injury">Acute Injury</option>
                                                    <option value="sports_injury">Sports Injury</option>
                                                    <option value="neck_pain">Neck Pain</option>
                                                    <option value="knee_injury">Knee Injury</option>
                                                    <option value="fracture_recovery">Fracture Recovery</option>
                                                    <option value="post_stroke">Post-Stroke Rehabilitation</option>
                                                    <option value="muscle_strain">Muscle Strain</option>
                                                    <option value="nerve_damage">Nerve Damage</option>
                                                </select>
                                            </div>
                                                        </div>
                                        </div>

                                        <div>
                                            <label class="block  font-semibold mb-1">Treatment</label>
                                            <select multiple
                                                id="treatment"
                                                name="treatment[]"
                                                data-hs-select='{
                                                    "placeholder": "Select treatment",
                                                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                                    "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-300 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500",
                                                    "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-300 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-white [&::-webkit-scrollbar-thumb]:bg-gray-300",
                                                    "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-200 rounded-lg focus:outline-hidden focus:bg-gray-200",
                                                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                                                    "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"}'class="hidden">
                                                <option value="reduce_pain">Reduce Pain</option>
                                                <option value="increase_flexibility">Increase Flexibility</option>
                                                <option value="enhance_strength">Enhance Strength</option>
                                                <option value="yoga_therapy">Yoga Therapy</option>
                                                <option value="flexibility_training">Flexibility Training</option>
                                                <option value="stretching">Stretching</option>
                                                <option value="breathing_exercises">Breathing Exercises</option>
                                                <option value="core_strengthening">Core Strengthening</option>
                                                <option value="manual_therapy">Manual Therapy</option>
                                                <option value="physiotherapy_exercises">Physiotherapy Exercises</option>
                                                <option value="postural_correction">Postural Correction</option>
                                                <option value="balance_training">Balance Training</option>
                                                <option value="range_of_motion">Range of Motion Exercises</option>
                                                <option value="heat_therapy">Heat Therapy</option>
                                                <option value="cold_therapy">Cold Therapy</option>
                                                <option value="electrotherapy">Electrotherapy</option>
                                                <option value="home_exercise_program">Home Exercise Program</option>
                                                <option value="patient_education">Patient Education</option>
                                            </select>
                                        </div>


                                        <div class="mb-4">
                                            <label class="font-semibold block mb-2">Additional Notes</label>
                                            <textarea name="notes" class="block w-full border border-gray-300 px-4 py-2 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3">{{ $record->notes ?? '' }}</textarea>
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 shadow">
                                                Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <script>
                                (function() {
                                    const appointmentId = {{ $app->id }};
                                    const selectedPlace = @json($recordPainLocation->toArray());
                                    const anatomyParts = document.querySelectorAll('#anatomy-' + appointmentId + ' [data-part]');
                                    const inputPlace = document.getElementById('place_of_injury_' + appointmentId);
                                    let selectedParts = selectedPlace || [];

                                    anatomyParts.forEach(part => {
                                        part.addEventListener('click', function () {
                                            const selected = this.getAttribute('data-part');
                                            
                                            if (selectedParts.includes(selected)) {
                                                selectedParts = selectedParts.filter(p => p !== selected);
                                                this.setAttribute('fill', '#E0E0E0');
                                            } else {
                                                selectedParts.push(selected);
                                                this.setAttribute('fill', '#60A5FA');
                                            }
                                            
                                            inputPlace.value = selectedParts.join(',');
                                        });

                                        if (selectedParts.includes(part.getAttribute('data-part'))) {
                                            part.setAttribute('fill', '#60A5FA');
                                        }
                                    });
                                })();
                            </script>
                        @else
                            <div class="space-y-4">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-center font-bold text-lg mb-4">APPOINTMENT INFORMATION</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="font-semibold block mb-2">Instructor</label>
                                            <div class="border px-3 py-2 bg-white rounded">{{ $app->staff->user->name ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <label class="font-semibold block mb-2">Service</label>
                                            <div class="border px-3 py-2 bg-white rounded">{{ $app->service->name ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <label class="font-semibold block mb-2">Date</label>
                                            <div class="border px-3 py-2 bg-white rounded">{{ $app->date }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-center font-bold text-lg mb-4">
                                        Appointment Medical Summary
                                    </h3>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-white rounded-lg p-3">
                                            <h4 class="text-center font-semibold text-sm mb-2">PAIN ASSESSMENT</h4>
                                            <div class="flex justify-center">
                                                <svg viewBox="0 0 200 300" style="width: 250px; height: 275px;" class="bg-gray-100 rounded">
                                                    <g class="stroke-gray-300" stroke-width="1">
                                                        <circle cx="100" cy="44" r="24"
                                                            fill="{{ $recordPainLocation->contains('Head') ? '#60A5FA' : '#E0E0E0' }}" />
                                                        <rect x="68" y="76" width="64" height="100" rx="16"
                                                            fill="{{ $recordPainLocation->contains('Chest') ? '#60A5FA' : '#E0E0E0' }}" />
                                                        <rect x="40" y="84" width="22" height="106" rx="11"
                                                            fill="{{ $recordPainLocation->contains('Left Arm') ? '#60A5FA' : '#E0E0E0' }}" />
                                                        <rect x="138" y="84" width="22" height="106" rx="11"
                                                            fill="{{ $recordPainLocation->contains('Right Arm') ? '#60A5FA' : '#E0E0E0' }}" />
                                                        <rect x="78" y="180" width="20" height="110" rx="10"
                                                            fill="{{ $recordPainLocation->contains('Left Leg') ? '#60A5FA' : '#E0E0E0' }}" />
                                                        <rect x="102" y="180" width="20" height="110" rx="10"
                                                            fill="{{ $recordPainLocation->contains('Right Leg') ? '#60A5FA' : '#E0E0E0' }}" />
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                      <div class="bg-white rounded-lg p-3">
                                            <h4 class="font-semibold text-sm mb-2">Reason of Injury</h4>
                                            <div class="space-y-2">
                                                @php
                                                    $reasons = is_array($record->diagnosis) ? $record->diagnosis : explode(',', $record->diagnosis ?? '');
                                                @endphp
                                                @forelse($reasons as $reason)
                                                    <div class="px-3 py-1 bg-gray-50 rounded text-sm">
                                                        {{ ucwords(str_replace('_',' ', trim($reason))) }}
                                                    </div>
                                                @empty
                                                    <div class="px-3 py-1 bg-gray-50 rounded text-sm">-</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-lg p-3">
                                            <h4 class="font-semibold text-sm mb-2">Type of Injury</h4>
                                            <div class="space-y-2">
                                                @php
                                                    $injuries = is_array($record->type_of_injury) ? $record->type_of_injury : explode(',', $record->type_of_injury ?? '');
                                                @endphp
                                                @forelse($injuries as $injury)
                                                    <div class="px-3 py-1 bg-gray-50 rounded text-sm">
                                                        {{ ucwords(str_replace('_',' ', trim($injury))) }}
                                                    </div>
                                                @empty
                                                    <div class="px-3 py-1 bg-gray-50 rounded text-sm">-</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="font-semibold block mb-2">Treatment / Intervention</label>
                                        <div class="space-y-2">
                                            @php
                                                $treatments = is_array($record->treatment) ? $record->treatment : explode(',', $record->treatment ?? '');
                                            @endphp
                                            @forelse($treatments as $treatment)
                                                <div class="px-4 py-2 bg-white rounded shadow-sm">
                                                    {{ ucwords(str_replace('_',' ', trim($treatment))) }}
                                                </div>
                                            @empty
                                                <div class="px-4 py-2 bg-white rounded shadow-sm">-</div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div>
                                        <label class="font-semibold block mb-2">Additional Notes</label>
                                        <div class="bg-white p-3 rounded shadow-sm">
                                            {{ $record->notes ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

<div id="updatePatientModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg w-4/5 max-w-3xl p-6 relative">

        <button type="button" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700" onclick="closeModal('updatePatientModal')">✕</button>

        <h2 class="text-xl font-bold mb-4">Update Patient Record</h2>

        <form method="POST" action="{{ route('patient.update', $patient->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold">Name</label>
                    <input type="text" name="name" value="{{ $patient->user->name ?? '' }}" class="border px-3 py-1 rounded w-full">
                </div>
                <div>
                    <label class="font-semibold">IC Number</label>
                    <input type="text" name="ic_number" value="{{ $patient->ICNumber }}" class="border px-3 py-1 rounded w-full">
                </div>
                <div>
                    <label class="font-semibold">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ $patient->phoneNumber }}" class="border px-3 py-1 rounded w-full">
                </div>
                <div>
                    <label class="font-semibold">Faculty</label>
                    <input type="text" name="faculty" value="{{ $patient->faculty }}" class="border px-3 py-1 rounded w-full">
                </div>
                <div>
                    <label class="font-semibold">Program</label>
                    <input type="text" name="program" value="{{ $patient->program ?? '' }}" class="border px-3 py-1 rounded w-full">
                </div>
                <div>
                    <label class="font-semibold">Category</label>
                    <select name="patient_type" class="border px-3 py-1 rounded w-full">
                        <option value="student" {{ $patient->category === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="uitm staff" {{ $patient->category === 'uitm staff' ? 'selected' : '' }}>UiTM Staff</option>
                        <option value="public" {{ $patient->category === 'public' ? 'selected' : '' }}>Public</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Save</button>
            </div>
        </form>

    </div>
</div>
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

</script>

</div>
@endsection
