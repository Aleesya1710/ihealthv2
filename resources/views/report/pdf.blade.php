<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin: 0 0 10px 0;
        }

        .header p {
            color: #666;
            margin: 5px 0;
            font-size: 11px;
        }

        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 5px 0;
        }

        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 15px;
            border: 1px solid #dee2e6;
        }

        .summary-item.primary {
            background-color: #007bff;
            color: white;
        }

        .summary-item.success {
            background-color: #28a745;
            color: white;
        }

        .summary-item.warning {
            background-color: #ffc107;
            color: white;
        }

        .summary-item.danger {
            background-color: #dc3545;
            color: white;
        }

        .summary-item h3 {
            font-size: 28px;
            margin: 0 0 5px 0;
        }

        .summary-item p {
            margin: 0;
            font-size: 11px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th {
            background-color: #343a40;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            font-size: 11px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            font-size: 10px;
            color: #666;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .revenue-box {
            background-color: #d4edda;
            border: 2px solid #28a745;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .revenue-box h4 {
            color: #28a745;
            margin: 0;
            font-size: 18px;
        }

        .page-break {
            page-break-after: always;
        }

        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $reportTitle }}</h1>
        <p><strong>{{ $reportSubtitle }}</strong></p>
        <p>Generated on: {{ date('d F Y, h:i A') }}</p>
    </div>

    <div class="no-break">
        <div class="section-title">Summary Statistics</div>
        <div class="summary-grid">
            <div class="summary-item primary">
                <h3>{{ $reportData['summary']['total'] }}</h3>
                <p>Total Appointments</p>
            </div>
            <div class="summary-item success">
                <h3>{{ $reportData['summary']['completed'] }}</h3>
                <p>Completed</p>
            </div>
            <div class="summary-item warning">
                <h3>{{ $reportData['summary']['upcoming'] ?? 0 }}</h3>
                <p>Upcoming</p>
            </div>
            <div class="summary-item danger">
                <h3>{{ $reportData['summary']['cancelled'] }}</h3>
                <p>Cancelled</p>
            </div>
        </div>
    </div>

    @if($reportType === 'service' && isset($reportData['summary']['revenue']))
        <div class="revenue-box no-break">
            <h4>Total Revenue: RM {{ number_format($reportData['summary']['revenue'], 2) }}</h4>
        </div>
    @endif

    @if($reportType === 'patient' && isset($reportData['patient']))
        <div class="no-break">
            <div class="section-title">Patient Information</div>
            <div class="info-box">
                <p><strong>Name:</strong> {{ $reportData['patient']->user?->name ?? 'N/A' }}</p>
                <p><strong>IC Number:</strong> {{ $reportData['patient']->ICNumber ?? 'N/A' }}</p>
                <p><strong>Contact:</strong> {{ $reportData['patient']->phoneNumber ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $reportData['patient']->user?->email ?? 'N/A' }}</p>
            </div>
        </div>
    @endif

    @if($reportType === 'staff' && isset($reportData['service_breakdown']))
        <div class="no-break">
            <div class="section-title">Service Breakdown</div>
            <table>
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th class="text-center">Number of Appointments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData['service_breakdown'] as $service)
                        <tr>
                            <td>{{ $service['service_name'] }}</td>
                            <td class="text-center">{{ $service['count'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if($reportType === 'service' && isset($reportData['staff_breakdown']))
        <div class="no-break">
            <div class="section-title">Staff Breakdown</div>
            <table>
                <thead>
                    <tr>
                        <th>Staff Name</th>
                        <th class="text-center">Number of Appointments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData['staff_breakdown'] as $staff)
                        <tr>
                            <td>{{ $staff['staff_name'] }}</td>
                            <td class="text-center">{{ $staff['count'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if($reportType === 'service' && isset($reportData['monthly_breakdown']))
        <div class="no-break">
            <div class="section-title">Monthly Breakdown</div>
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-center">Number of Appointments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData['monthly_breakdown'] as $month)
                        <tr>
                            <td>{{ $month['month'] }}</td>
                            <td class="text-center">{{ $month['count'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if($reportType === 'patient' && isset($reportData['service_history']))
        <div class="no-break">
            <div class="section-title">Service History</div>
            <table>
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th class="text-center">Visit Count</th>
                        <th class="text-center">Last Visit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData['service_history'] as $service)
                        <tr>
                            <td>{{ $service['service_name'] }}</td>
                            <td class="text-center">{{ $service['count'] }}</td>
                            <td class="text-center">{{ date('d M Y', strtotime($service['last_visit'])) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No service history</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @php
        $fields = $selectedFields ?? ['date','time','patient','service','staff','status','record'];
    @endphp
    <div class="section-title">Appointment Details</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                @if(in_array('date', $fields))
                    <th style="width: 12%;">Date</th>
                @endif
                @if(in_array('time', $fields))
                    <th style="width: 10%;">Time</th>
                @endif
                @if(in_array('patient', $fields) && ($reportType !== 'patient' || $isAllScope))
                    <th style="width: 15%;">Patient</th>
                @endif
                @if(in_array('service', $fields) && ($reportType !== 'service' || $isAllScope))
                    <th style="width: 15%;">Service</th>
                @endif
                @if(in_array('staff', $fields) && ($reportType !== 'staff' || $isAllScope))
                    <th style="width: 15%;">Staff</th>
                @endif
                @if(in_array('status', $fields))
                    <th style="width: 10%;">Status</th>
                @endif
                @if(in_array('record', $fields))
                    <th>Record Details</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['appointments'] as $index => $appointment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @if(in_array('date', $fields))
                        <td>{{ date('d M Y', strtotime($appointment->date)) }}</td>
                    @endif
                    @if(in_array('time', $fields))
                        <td>{{ date('h:i A', strtotime($appointment->time)) }}</td>
                    @endif
                    @if(in_array('patient', $fields) && ($reportType !== 'patient' || $isAllScope))
                        <td>{{ $appointment->patientRecord?->customer?->user?->name ?? 'N/A' }}</td>
                    @endif
                    @if(in_array('service', $fields) && ($reportType !== 'service' || $isAllScope))
                        <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                    @endif
                    @if(in_array('staff', $fields) && ($reportType !== 'staff' || $isAllScope))
                        <td>{{ $appointment->staff?->user?->name ?? 'N/A' }}</td>
                    @endif
                    @if(in_array('status', $fields))
                        <td>
                            @if($appointment->status === 'completed')
                                <span class="badge badge-success">Completed</span>
                            @elseif($appointment->status === 'cancelled')
                                <span class="badge badge-danger">Cancelled</span>
                            @else
                                <span class="badge badge-warning">Upcoming</span>
                            @endif
                        </td>
                    @endif
                    @if(in_array('record', $fields))
                        <td>
                            @php
                                $record = $appointment->patientRecord;
                                $symptoms = is_array($record?->symptoms ?? null) ? implode(', ', $record->symptoms) : null;
                                $diagnosis = is_array($record?->diagnosis ?? null) ? implode(', ', $record->diagnosis) : null;
                                $treatment = is_array($record?->treatment ?? null) ? implode(', ', $record->treatment) : null;
                                $injury = is_array($record?->type_of_injury ?? null) ? implode(', ', $record->type_of_injury) : null;
                            @endphp
                            <div>
                                <strong>Notes:</strong> {{ $record?->notes ?? '-' }}<br>
                                <strong>Symptoms:</strong> {{ $symptoms ?? '-' }}<br>
                                <strong>Diagnosis:</strong> {{ $diagnosis ?? '-' }}<br>
                                <strong>Treatment:</strong> {{ $treatment ?? '-' }}<br>
                                <strong>Injury:</strong> {{ $injury ?? '-' }}
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No appointments found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was automatically generated by the Clinic Management System</p>
        <p>&copy; {{ date('Y') }} - All Rights Reserved</p>
    </div>
</body>
</html>
