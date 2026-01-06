@extends('layouts.layoutS')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-8 mt-10 rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-center mb-8 text-[#104F5D]">Appointment Details</h1>

    {{-- Patient Info --}}
    <div class="mb-8 border-b pb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Patient Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800">
            <p><strong>Name:</strong> {{ $appointment->patient->name ?? '-' }}</p>
            <p><strong>IC:</strong> {{ $appointment->patient->ic_number ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $appointment->patient->contact_number ?? '-' }}</p>
            <p><strong>Age:</strong> {{ $appointment->patient->age ?? '-' }}</p>
        </div>

       @if ($patientrecord && $patientrecord->referral_letter)
            <div class="mt-6 bg-blue-50 border border-blue-200 p-4 rounded-lg">
                <h3 class="font-semibold text-blue-900 mb-2">Referral Letter</h3>
                <a href="{{ asset('storage/' . $patientrecord->referral_letter) }}"
                target="_blank"
                class="inline-block text-blue-600 hover:underline font-medium">
                    📄 View Referral Letter
                </a>
            </div>
        @endif

    </div>

    {{-- Appointment Info --}}
   <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Appointment Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800">
            <div class="md:col-span-2">
                <label class="font-medium">Service:</label>
                <div class="bg-gray-100 p-2 rounded">{{ $appointment->service->name ?? '-' }}</div>
            </div>
            <div>
                <label class="font-medium">Date:</label>
                <div class="bg-gray-100 p-2 rounded">{{ $appointment->date }}</div>
            </div>
            <div>
                <label class="font-medium">Time:</label>
                <div class="bg-gray-100 p-2 rounded">{{ $appointment->time }}</div>
            </div>
        </div>
    </div>


    {{-- Treatment Plan Form --}}
    <form method="POST" action="{{ route('updateappointment',  ['id' => $appointment->patient_id, 'appointmentId' => $appointment->id]) }}">
        @csrf
        @method('PUT')
        <div class="mb-6">
            <label for="treatment" class="block font-semibold mb-2">Treatment / Intervention</label>
            <div>
                <select multiple
                    id="treatment_goals"
                    name="treatment_goals[]"
                    data-hs-select='{
                        "placeholder": "Select treatment goals...",
                        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-gray-100 border border-gray-300 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500",
                        "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-gray-100 border border-gray-300 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300",
                        "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-200 rounded-lg focus:outline-hidden focus:bg-gray-200",
                        "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                        "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                    }'
                    class="hidden">
                    <option value="">Choose</option>
                    <option value="reduce_pain">Reduce Pain</option>
                    <option value="increase_flexibility">Increase Flexibility</option>
                    <option value="improve_sleep">Improve Sleep</option>
                    <option value="enhance_strength">Enhance Strength</option>
                    <option value="stress_relief">Stress Relief</option>
                </select>
            </div>

            <div class="mt-6">
                <label for="treatment_notes" class="block mb-1 text-sm font-medium text-gray-700">Additional Notes <span class="text-gray-400 text-xs italic">(Optional)</span></label>
                <textarea
                    id="treatment_notes"
                    name="treatment_notes"
                    rows="4"
                    placeholder="Write additional comments or goals..."
                    class="block w-full p-3 text-sm text-gray-800 border border-gray-300 rounded-lg bg-gray-100 focus:ring-blue-500 focus:border-blue-500"
                ></textarea>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-[#104F5D] hover:bg-[#1d3a41] text-white font-semibold px-6 py-2 rounded-lg shadow">
                Save Treatment
            </button>
        </div>
    </form>
</div>
@endsection
