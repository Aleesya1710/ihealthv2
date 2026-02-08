@component('mail::message')
# Appointment Reminder

Hello {{ $name }},

This is a friendly reminder that you have an appointment tomorrow.

@component('mail::panel')
**Service:** {{ $serviceName }}  
**Date:** {{ $date }}  
**Time:** {{ $time }}  
**Instructor:** {{ $staffName }}  
@endcomponent

Please bring your referral letter (if applicable) on the appointment day.

@component('mail::button', ['url' => $actionUrl])
View My Appointments
@endcomponent

If you need to reschedule, please do so at least 24 hours in advance.

Thanks,  
{{ config('app.name', 'iHealthPortal') }}
@endcomponent
