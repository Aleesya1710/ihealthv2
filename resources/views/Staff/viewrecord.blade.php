@extends('layouts.layoutS')

@section('content')
<div class="bg-gray-300 p-6 rounded-2xl max-w-5xl mx-auto shadow-md">
    {{-- Top Bar --}}
    <div class="flex justify-between mb-5">
        <div class="bg-white px-5 py-2 w-40 rounded-lg inline-block font-semibold">{{$patient->id}}</div>
        <div>
        <a href="{{ route('patient.edit', $patient->id) }}"
        class="justify-center inline-block bg-[#104F5D] hover:bg-[#1d3a41] text-white font-semibold px-5 py-2 rounded-lg shadow transition duration-200">
            Update 
        </a>
        <a href="{{ route('patients.report.preview', $patient->id) }}"class="inline-block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            🧾 Download Report PDF
        </a>
        </div>
    </div>
    


    {{-- Patient Information --}}
    <div class="bg-white rounded-lg p-8 mb-4">
        <h2 class="text-center font-bold text-lg mb-3">PATIENT INFORMATION</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div><label class="font-semibold">Name</label><div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->name }}</div></div>
            <div><label class="font-semibold">IC Number</label><div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->ic_number }}</div></div>
            <div><label class="font-semibold">Age</label><div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->age }}</div></div>
            <div><label class="font-semibold">Gender</label><div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->gender }}</div></div>
            <div><label class="font-semibold">Contact Number</label><div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->contact_number }}</div></div>
            <div><label class="font-semibold">Patient Type</label><div class="border px-3 py-1 my-4 bg-gray-100 rounded">{{ $patient->patient_type }}</div></div>
        </div>
    </div>
{{-- Medical History --}}
    <div class="bg-white rounded-lg p-6 mb-6 shadow">
        <h2 class="text-center font-bold text-2xl text-gray-800 mb-6">Medical Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Reason of Visit</label>
                    @if(!empty($patient->reason_of_visit))
                        <ul class="list-disc list-inside">
                            @foreach(json_decode($patient->reason_of_visit, true) as $reason)
                                <div class="px-4 py-2 bg-gray-100 rounded-lg shadow-sm text-gray-800 mb-2">
                                    {{ ucwords(str_replace('_', ' ', $reason)) }}
                                </div>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Type of Injury</label>
                    @if(!empty($patient->type_of_injury))
                        <ul class="list-disc list-inside">
                            @foreach(json_decode($patient->type_of_injury, true) as $type)
                                <div class="px-4 py-2 bg-gray-100 rounded-lg shadow-sm text-gray-800 mb-2">
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </div>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
            </div>
        </div>
    </div>

@php
    $painLocation = $patient->place_of_injury ?? '';
@endphp
    {{-- Pain Assessment + Treatment Plan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Pain Assessment --}}
        <div class="bg-white rounded-lg p-4">
            <h2 class="text-center font-bold text-lg mb-3">PAIN ASSESSMENT</h2>
             <svg id="anatomy" viewBox="0 0 200 300" class="w-full h-auto bg-gray-100 rounded">
                <!-- Head -->
                <circle id="head" cx="100" cy="40" r="30" fill="{{ $painLocation === 'Head' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Chest -->
                <rect id="chest" x="70" y="80" width="60" height="80" fill="{{ $painLocation === 'Chest' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Left Arm -->
                <rect id="left-arm" x="30" y="80" width="30" height="100" fill="{{ $painLocation === 'Left Arm' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Right Arm -->
                <rect id="right-arm" x="140" y="80" width="30" height="100" fill="{{ $painLocation === 'Right Arm' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Left Leg -->
                <rect id="left-leg" x="70" y="180" width="25" height="100" fill="{{ $painLocation === 'Left Leg' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />

                <!-- Right Leg -->
                <rect id="right-leg" x="105" y="180" width="25" height="100" fill="{{ $painLocation === 'Right Leg' ? '#60A5FA' : '#E0E0E0' }}" class="hover:fill-blue-300 cursor-pointer" />
            </svg>
        </div>

        {{-- Treatment Plan & Intervention --}}
        <div class="bg-white rounded-lg p-4">
            <div class="mb-10">
                <h3 class="text-center font-bold text-lg mb-2">TREATMENT PLAN</h3>
                @php $shownTreatments = []; @endphp

                @foreach ($patientrecord as $record)
                    @php
                        $treatments = json_decode($record->treatment, true) ?? [];
                    @endphp

                    @foreach ($treatments as $treatment)
                        @if (!in_array($treatment, $shownTreatments))
                            @php $shownTreatments[] = $treatment; @endphp
                        @endif
                    @endforeach
                @endforeach

                @if (count($shownTreatments) > 0)
                    <ul class="space-y-2">
                        @foreach ($shownTreatments as $treatment)
                            <li class="border rounded px-3 py-2 bg-gray-100">{{ ucwords(str_replace('_', ' ', $treatment)) }}</li>
                        @endforeach
                    </ul>
                @else
                    <div class="border rounded px-3 py-2 bg-gray-100">
                        -
                    </div>
                @endif
            </div>

           
            <div>
                <h3 class="text-center font-semibold mb-2">TREATMENT / INTERVENTION</h3>
                <ul class="space-y-2">
                    @php $shownServices = []; @endphp
                    @foreach ($appointments as $app)
                        @php $serviceName = $app->service->name; @endphp
                        @if (!in_array($serviceName, $shownServices))
                            <li class="border rounded px-3 py-2 bg-gray-100">{{ $serviceName }}</li>
                            @php $shownServices[] = $serviceName; @endphp
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Appointment History --}}
<div class="mt-8">
    <h2 class="text-center font-bold text-lg mb-3">APPOINTMENT HISTORY</h2>
    @foreach ($appointments as $app)
        <div class="bg-white rounded-lg p-4 mb-4 shadow flex justify-between items-start">
            {{-- Appointment Details --}}
            <div class="grid grid-cols-2 gap-4 w-full pr-6">
                <div>
                    <label class="font-semibold">Appointment ID</label>
                    <div>{{ $app->id }}</div>
                </div>
                <div>
                    <label class="font-semibold">Date</label>
                    <div>{{ $app->date }}</div>
                </div>
                <div>
                    <label class="font-semibold">Time</label>
                    <div>{{ $app->time }}</div>
                </div>
                <div>
                    <label class="font-semibold">Status</label>
                    <div>{{ $app->status }}</div>
                </div>
                <div>
                    <label class="font-semibold">Service</label>
                    <div>{{ $app->service->name ?? '-' }}</div>
                </div>
            </div>

          <!-- View Button (opens modal) -->
            <a href="#" 
            data-modal-target="modal-{{ $app->id }}" 
            data-modal-toggle="modal-{{ $app->id }}" 
            title="View" 
            class="text-blue-500 text-xl hover:scale-110 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
            </svg>
            </a>

            @if($app->status == 'upcoming')
            <!-- Edit Button (redirects to edit page) -->
            <a href="{{ route('editappointment', ['id' => $app->patient_id, 'appointmentId' => $app->id]) }}"

            title="Edit" 
            class="text-green-600 text-xl hover:scale-110 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5l3 3L14 13H11v-3l7.5-7.5z" />
            </svg>
            </a>
            @endif
        </div>
        
        <!-- View Modal -->
<div id="modal-{{ $app->id }}" tabindex="-1" aria-hidden="true"
     class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg relative">
        <button data-modal-hide="modal-{{ $app->id }}" class="absolute top-2 right-2 text-gray-400 hover:text-gray-800 text-xl">×</button>

        <h3 class="text-xl font-bold mb-4 text-center text-indigo-700">Appointment Details</h3>

        @php
            $record = $patientrecord->where('appointment_id', $app->id)->first();
            $goals = $record ? json_decode($record->treatment, true) : [];
        @endphp

        <div class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><strong>ID:</strong> {{ $app->id }}</div>
                <div><strong>Date:</strong> {{ $app->date }}</div>
                <div><strong>Time:</strong> {{ $app->time }}</div>
                <div><strong>Status:</strong> {{ $app->status }}</div>
                <div><strong>Service:</strong> {{ $app->service->name ?? '-' }}</div>

               <div class="md:col-span-2">
                <strong>Treatment:</strong>
                <div class="bg-gray-100 p-3 rounded text-sm text-gray-700 whitespace-pre-line mt-1">
                    @if (!empty($goals))
                        {{ implode("\n", array_map(function($goal) {
                            return match($goal) {
                                'reduce_pain' => 'Reduce Pain',
                                'increase_flexibility' => 'Increase Flexibility',
                                'improve_sleep' => 'Improve Sleep',
                                'enhance_strength' => 'Enhance Strength',
                                'stress_relief' => 'Stress Relief',
                                default => ucfirst(str_replace('_', ' ', $goal))
                            };
                        }, $goals)) }}
                    @else
                        -
                    @endif
                </div>
            </div>

            </div>

            <div>
                <strong>Notes:</strong>
                <div class="bg-gray-100 p-3 rounded text-sm text-gray-700 whitespace-pre-line mt-1">
                    {{ $record->notes ?? '-' }}
                </div>
            </div>

            @if ($record && $record->referral_letter)
                <div class="mt-4 bg-blue-50 border border-blue-200 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">Referral Letter</h4>
                    <a href="{{ asset('storage/' . $record->referral_letter) }}" target="_blank"
                       class="inline-block text-blue-600 hover:underline font-medium">
                        📄 View Referral Letter
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

            @endforeach
        </div>
</div>

@endsection