@extends('layouts.layoutS')

@section('content')
<div class="p-6 md:p-10">
    <div class="max-w-4xl mx-auto">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-black/5 overflow-hidden">
            <div class="px-6 py-6 bg-[#10859F] text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold">Report Management</h2>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6 space-y-6">
                @if(session('error'))
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('report.generate') }}" method="POST" id="reportForm" class="space-y-6">
                    @csrf

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">1. Information to Include</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @php
                                $fieldOptions = [
                                    'date' => 'Date',
                                    'time' => 'Time',
                                    'patient' => 'Patient Name',
                                    'service' => 'Service',
                                    'staff' => 'Staff Name',
                                    'status' => 'Status',
                                    'record' => 'Record Details',
                                ];
                                $oldFields = old('fields', array_keys($fieldOptions));
                            @endphp
                            @foreach ($fieldOptions as $key => $label)
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="fields[]" value="{{ $key }}" class="rounded border-gray-300 text-[#10859F]"
                                        {{ in_array($key, $oldFields) ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('fields')
                            <div class="text-xs text-red-600 mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">2. Report Type</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="group">
                                <input type="radio" name="report_type" value="staff" class="peer sr-only" {{ old('report_type') === 'staff' ? 'checked' : '' }} required>
                                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition group-hover:shadow-md peer-checked:border-[#10859F] peer-checked:ring-2 peer-checked:ring-[#10859F]/30">
                                    <div class="text-sm font-semibold text-gray-800">By Staff</div>
                                    <div class="text-xs text-gray-500 mt-1">Appointments handled by a staff member</div>
                                </div>
                            </label>
                            <label class="group">
                                <input type="radio" name="report_type" value="service" class="peer sr-only" {{ old('report_type') === 'service' ? 'checked' : '' }} required>
                                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition group-hover:shadow-md peer-checked:border-[#10859F] peer-checked:ring-2 peer-checked:ring-[#10859F]/30">
                                    <div class="text-sm font-semibold text-gray-800">By Service</div>
                                    <div class="text-xs text-gray-500 mt-1">Appointments filtered by service</div>
                                </div>
                            </label>
                            <label class="group">
                                <input type="radio" name="report_type" value="patient" class="peer sr-only" {{ old('report_type') === 'patient' ? 'checked' : '' }} required>
                                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition group-hover:shadow-md peer-checked:border-[#10859F] peer-checked:ring-2 peer-checked:ring-[#10859F]/30">
                                    <div class="text-sm font-semibold text-gray-800">By Patient</div>
                                    <div class="text-xs text-gray-500 mt-1">Full record for a selected patient</div>
                                </div>
                            </label>
                        </div>
                        @error('report_type')
                            <div class="text-xs text-red-600 mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="staffSection" class="hidden rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">3. Staff Selection</h3>
                        <label class="text-xs text-gray-600">Select Staff</label>
                        <select name="staff_id" id="staff_id" class="mt-1 w-full rounded-lg border-gray-300">
                            <option value="all">All Staff</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->staffID }}" {{ old('staff_id') == $staff->staffID ? 'selected' : '' }}>
                                    {{ $staff->user->name ?? 'Unknown' }} ({{ $staff->position ?? 'Staff' }})
                                </option>
                            @endforeach
                        </select>
                        @error('staff_id')
                            <div class="text-xs text-red-600 mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="serviceSection" class="hidden rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">3. Service Selection</h3>
                        <label class="text-xs text-gray-600">Select Service</label>
                        <select name="service_id" id="service_id" class="mt-1 w-full rounded-lg border-gray-300">
                            <option value="all">All Services</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="text-xs text-red-600 mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="patientSection" class="hidden rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">3. Patient Selection</h3>
                        <label class="text-xs text-gray-600">Select Patient</label>
                        <select name="patient_id" id="patient_id" class="mt-1 w-full rounded-lg border-gray-300">
                            <option value="all">All Patients</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->user->name ?? 'Unknown' }} (IC: {{ $patient->ICNumber ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="text-xs text-red-600 mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">4. Date Range (Optional)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-gray-600">From</label>
                                <input type="date" name="date_from" id="date_from" value="{{ old('date_from') }}" class="mt-1 w-full rounded-lg border-gray-300">
                                @error('date_from')
                                    <div class="text-xs text-red-600 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-600">To</label>
                                <input type="date" name="date_to" id="date_to" value="{{ old('date_to') }}" class="mt-1 w-full rounded-lg border-gray-300">
                                @error('date_to')
                                    <div class="text-xs text-red-600 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('dashboardS') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Back to Dashboard
                        </a>
                        <button type="submit" class="inline-flex items-center rounded-lg bg-[#10859F] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[#0E6E83]">
                            Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const sections = {
            staff: document.getElementById('staffSection'),
            service: document.getElementById('serviceSection'),
            patient: document.getElementById('patientSection'),
        };

        const inputs = {
            staff: document.getElementById('staff_id'),
            service: document.getElementById('service_id'),
            patient: document.getElementById('patient_id'),
        };

        function setActive(type) {
            Object.keys(sections).forEach(key => {
                const isActive = key === type;
                sections[key].classList.toggle('hidden', !isActive);
                inputs[key].required = isActive;
            });
        }

        document.querySelectorAll('input[name="report_type"]').forEach(radio => {
            radio.addEventListener('change', () => setActive(radio.value));
        });

        const initial = document.querySelector('input[name="report_type"]:checked');
        if (initial) setActive(initial.value);
    })();
</script>
@endsection
