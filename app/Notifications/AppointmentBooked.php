<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AppointmentBooked extends Notification
{
    protected $appointment;
    protected string $audience;
    use Queueable;

    public function __construct($appointment, string $audience = 'customer')
    {
        $this->appointment = $appointment;
        $this->audience = $audience;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $date = Carbon::parse($this->appointment->date)->format('l, d M Y');
        $time = Carbon::parse($this->appointment->time)->format('h:i A');
        $serviceName = $this->appointment->service->name ?? 'N/A';
        $staffName = $this->appointment->staff?->user?->name ?? 'N/A';
        $patientName = $this->appointment->patientRecord?->customer?->user?->name ?? 'N/A';
        $actionUrl = $this->audience === 'staff'
            ? route('appoinmentmanagement')
            : route('appointmenthistory', $notifiable->id);

        return (new MailMessage)
            ->subject($this->audience === 'staff' ? 'New Appointment Assigned' : 'Appointment Confirmed')
            ->markdown('emails.appointments.booked', [
                'audience' => $this->audience,
                'name' => $notifiable->name ?? '',
                'patientName' => $patientName,
                'staffName' => $staffName,
                'serviceName' => $serviceName,
                'date' => $date,
                'time' => $time,
                'actionUrl' => $actionUrl,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
        ];
    }
}
