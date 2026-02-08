@component('mail::message')
# {{ $audience === 'staff' ? 'New Appointment Assigned' : 'Appointment Confirmed' }}

Hello {{ $name }},

@if ($audience === 'staff')
You have a new appointment assigned. Please review the details below.
@else
Your appointment is confirmed. Please review the details below.
@endif

@component('mail::panel')
**Service:** {{ $serviceName }}  
**Date:** {{ $date }}  
**Time:** {{ $time }}  
@if ($audience === 'staff')
**Patient:** {{ $patientName }}  
@else
**Instructor:** {{ $staffName }}  
@endif
@endcomponent

@component('mail::button', ['url' => $actionUrl])
{{ $audience === 'staff' ? 'View Appointments' : 'View My Appointments' }}
@endcomponent

@if ($audience === 'staff')
Please prepare accordingly and reach out if you need any additional information.
@else
Please bring your referral letter (if applicable) on the appointment day.
We will also send you a reminder 1 day before your appointment.

If you need to reschedule, please do so at least 24 hours in advance.
@endif

Thanks,  
{{ config('app.name', 'iHealthPortal') }}
@endcomponent
