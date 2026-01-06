<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #000;
            font-size: 12px;
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #444;
        }

        .section {
            margin-top: 20px;
        }

        .section h3 {
            background-color: #f0f0f0;
            padding: 5px;
            border-left: 4px solid #007BFF;
        }

        .info {
            margin-top: 10px;
            padding-left: 10px;
        }

        .info p {
            margin: 4px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th, .table td {
            border: 1px solid #999;
            padding: 6px 10px;
            text-align: left;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .table th {
            background-color: #007BFF;
            color: #fff;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        
        
    </style>
</head>
<body>

    <div class="header">
        <h2>Patient Report</h2>
        <p>Sport & Wellness Clinic FSR | iHealthPortal</p>
    </div>

   <div class="section">
    <h3>Patient Information</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr>
            <td style="padding: 5px;"><strong>Name:</strong> {{ $patient->name }}</td>
            <td style="padding: 5px;"><strong>IC:</strong> {{ $patient->ic_number }}</td>
            <td style="padding: 5px;"><strong>Age:</strong> {{ $patient->age }}</td>
        </tr>
        <tr>
            <td style="padding: 5px;"><strong>Phone:</strong> {{ $patient->contact_number }}</td>
            <td style="padding: 5px;"><strong>Patient Type:</strong> {{ $patient->patient_type }}</td>
            <td style="padding: 5px;"><strong>Gender:</strong> {{ $patient->gender }}</td>
        </tr>
    </table>
</div>

   <div class="section">
    <h3>Medical Records</h3>
    <table style="width: 100%; border: 1px solid #ccc; border-collapse: collapse; margin-top: 10px;">
        <tr>
            <!-- 📍 Image on the Left -->
            <td style="width: 200px; padding: 10px; vertical-align: top; border-right: 1px solid #ccc;">
                <p><strong>Place of Injury:</strong></p>
                @php
                    $imagePath = $isPDF 
                        ? public_path('image/body_image.png') 
                        : asset('image/body_image.png');
                @endphp

                <img src="{{ $imagePath }}" alt="Body Image" style="width: 100%; max-width: 200px;">
            </td>

            <!-- 🗒️ Details on the Right -->
            <td style="padding: 10px; vertical-align: top;">
                <p><strong>Reason of Visit:</strong></p>
                <ul style="margin-left: 15px;">
                    @foreach (json_decode($patient->reason_of_visit) ?? [] as $reason)
                        <li>{{ ucwords(str_replace('_', ' ', $reason)) }}</li>
                    @endforeach
                </ul>

                <p><strong>Type of Injury:</strong></p>
                <ul style="margin-left: 15px;">
                    @foreach (json_decode($patient->type_of_injury) ?? [] as $type)
                        <li>{{ ucwords(str_replace('_', ' ', $type)) }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    </table>
</div>




    <div class="section">
        <h3>Appointments History</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Service</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $app)
                    <tr>
                        <td>{{ $app->date }}</td>
                        <td>{{ $app->service->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($app->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>

</body>
</html>
