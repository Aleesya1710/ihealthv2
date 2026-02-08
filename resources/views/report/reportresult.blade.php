@extends('layouts.layoutS')

@section('content')
<div class="p-6 md:p-10">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-black/5 overflow-hidden">
            <div class="px-6 py-6 bg-[#10859F] text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold">{{ $reportTitle }}</h2>
                        <p class="text-sm text-white/80 mt-1">{{ $reportSubtitle }}</p>
                    </div>
                    <form action="{{ route('report.download') }}" method="POST" class="shrink-0">
                        @csrf
                        <input type="hidden" name="report_type" value="{{ $reportType }}">
                        @if($reportType === 'staff')
                            <input type="hidden" name="staff_id" value="{{ request('staff_id') }}">
                        @elseif($reportType === 'service')
                            <input type="hidden" name="service_id" value="{{ request('service_id') }}">
                        @elseif($reportType === 'patient')
                            <input type="hidden" name="patient_id" value="{{ request('patient_id') }}">
                        @endif
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                        @if(!empty($selectedFields))
                            @foreach($selectedFields as $f)
                                <input type="hidden" name="fields[]" value="{{ $f }}">
                            @endforeach
                        @endif
                        <button type="submit" class="inline-flex items-center rounded-lg bg-white/10 px-4 py-2 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/20">
                            Download PDF
                        </button>
                    </form>
                </div>
            </div>

            <div class="px-6 py-6 space-y-6">
                <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    Generated on {{ date('d M Y, h:i A') }}
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="rounded-xl bg-[#0E6E83] text-white p-4">
                        <div class="text-2xl font-semibold">{{ $reportData['summary']['total'] ?? 0 }}</div>
                        <div class="text-xs opacity-80">Total Appointments</div>
                    </div>
                    <div class="rounded-xl bg-green-600 text-white p-4">
                        <div class="text-2xl font-semibold">{{ $reportData['summary']['completed'] ?? 0 }}</div>
                        <div class="text-xs opacity-80">Completed</div>
                    </div>
                    <div class="rounded-xl bg-amber-500 text-white p-4">
                        <div class="text-2xl font-semibold">{{ $reportData['summary']['upcoming'] ?? 0 }}</div>
                        <div class="text-xs opacity-80">Upcoming</div>
                    </div>
                    <div class="rounded-xl bg-red-600 text-white p-4">
                        <div class="text-2xl font-semibold">{{ $reportData['summary']['cancelled'] ?? 0 }}</div>
                        <div class="text-xs opacity-80">Cancelled</div>
                    </div>
                </div>

                @if($reportType === 'staff' && isset($reportData['service_breakdown']))
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Service Breakdown</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b">
                                        <th class="py-2 pr-4">Service</th>
                                        <th class="py-2">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData['service_breakdown'] as $service)
                                        <tr class="border-b last:border-b-0">
                                            <td class="py-2 pr-4 text-gray-800">{{ $service['service_name'] }}</td>
                                            <td class="py-2 text-gray-600">{{ $service['count'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="py-3 text-center text-gray-500">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($reportType === 'service' && isset($reportData['staff_breakdown']))
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Staff Breakdown</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b">
                                        <th class="py-2 pr-4">Staff</th>
                                        <th class="py-2">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData['staff_breakdown'] as $staff)
                                        <tr class="border-b last:border-b-0">
                                            <td class="py-2 pr-4 text-gray-800">{{ $staff['staff_name'] }}</td>
                                            <td class="py-2 text-gray-600">{{ $staff['count'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="py-3 text-center text-gray-500">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($reportType === 'patient' && isset($reportData['service_history']))
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Service History</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b">
                                        <th class="py-2 pr-4">Service</th>
                                        <th class="py-2">Visit Count</th>
                                        <th class="py-2">Last Visit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData['service_history'] as $service)
                                        <tr class="border-b last:border-b-0">
                                            <td class="py-2 pr-4 text-gray-800">{{ $service['service_name'] }}</td>
                                            <td class="py-2 text-gray-600">{{ $service['count'] }}</td>
                                            <td class="py-2 text-gray-600">{{ date('d M Y', strtotime($service['last_visit'])) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-3 text-center text-gray-500">No service history</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="rounded-xl border border-gray-200 bg-white p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Appointment Details</h3>
                    <div class="overflow-x-auto">
                        @php
                            $fields = $selectedFields ?? ['date','time','patient','service','staff','status','record'];
                        @endphp
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2 pr-4">#</th>
                                    @if(in_array('date', $fields))
                                        <th class="py-2 pr-4">Date</th>
                                    @endif
                                    @if(in_array('time', $fields))
                                        <th class="py-2 pr-4">Time</th>
                                    @endif
                        @if(in_array('patient', $fields) && ($reportType !== 'patient' || $isAllScope))
                            <th class="py-2 pr-4">Patient</th>
                        @endif
                        @if(in_array('service', $fields) && ($reportType !== 'service' || $isAllScope))
                            <th class="py-2 pr-4">Service</th>
                        @endif
                        @if(in_array('staff', $fields) && ($reportType !== 'staff' || $isAllScope))
                            <th class="py-2 pr-4">Staff</th>
                        @endif
                                    @if(in_array('status', $fields))
                                        <th class="py-2 pr-4">Status</th>
                                    @endif
                                    @if(in_array('record', $fields))
                                        <th class="py-2">Record Details</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData['appointments'] as $index => $appointment)
                                    <tr class="border-b last:border-b-0">
                                        <td class="py-2 pr-4 text-gray-700">{{ $index + 1 }}</td>
                                        @if(in_array('date', $fields))
                                            <td class="py-2 pr-4 text-gray-700">{{ date('d M Y', strtotime($appointment->date)) }}</td>
                                        @endif
                                        @if(in_array('time', $fields))
                                            <td class="py-2 pr-4 text-gray-700">{{ date('h:i A', strtotime($appointment->time)) }}</td>
                                        @endif
                                        @if(in_array('patient', $fields) && ($reportType !== 'patient' || $isAllScope))
                                            <td class="py-2 pr-4 text-gray-700">
                                                {{ $appointment->patientRecord?->customer?->user?->name ?? 'N/A' }}
                                            </td>
                                        @endif
                                        @if(in_array('service', $fields) && ($reportType !== 'service' || $isAllScope))
                                            <td class="py-2 pr-4 text-gray-700">{{ $appointment->service->name ?? 'N/A' }}</td>
                                        @endif
                                        @if(in_array('staff', $fields) && ($reportType !== 'staff' || $isAllScope))
                                            <td class="py-2 pr-4 text-gray-700">{{ $appointment->staff?->user?->name ?? 'N/A' }}</td>
                                        @endif
                                        @if(in_array('status', $fields))
                                            <td class="py-2 pr-4">
                                                @if($appointment->status === 'completed')
                                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Completed</span>
                                                @elseif($appointment->status === 'cancelled')
                                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Cancelled</span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">Upcoming</span>
                                                @endif
                                            </td>
                                        @endif
                                        @if(in_array('record', $fields))
                                            <td class="py-2 text-gray-700">
                                                @php
                                                    $record = $appointment->patientRecord;
                                                    $symptoms = is_array($record?->symptoms ?? null) ? implode(', ', $record->symptoms) : null;
                                                    $diagnosis = is_array($record?->diagnosis ?? null) ? implode(', ', $record->diagnosis) : null;
                                                    $treatment = is_array($record?->treatment ?? null) ? implode(', ', $record->treatment) : null;
                                                    $injury = is_array($record?->type_of_injury ?? null) ? implode(', ', $record->type_of_injury) : null;
                                                @endphp
                                                <div class="text-xs text-gray-700 space-y-1">
                                                    <div><span class="font-semibold">Notes:</span> {{ $record?->notes ?? '-' }}</div>
                                                    <div><span class="font-semibold">Symptoms:</span> {{ $symptoms ?? '-' }}</div>
                                                    <div><span class="font-semibold">Diagnosis:</span> {{ $diagnosis ?? '-' }}</div>
                                                    <div><span class="font-semibold">Treatment:</span> {{ $treatment ?? '-' }}</div>
                                                    <div><span class="font-semibold">Injury:</span> {{ $injury ?? '-' }}</div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-3 text-center text-gray-500">No appointments found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('report.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Back to Reports
                    </a>
                    <button onclick="window.print()" class="text-sm font-semibold text-[#10859F] hover:text-[#0E6E83]">
                        Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        nav, aside, .print-hide, button, a[href*="report.index"] {
            display: none !important;
        }
    }
</style>
@endsection
