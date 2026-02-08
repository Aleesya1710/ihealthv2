<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class AppointmentReminder extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
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

        return (new MailMessage)
            ->subject('Appointment Reminder')
            ->markdown('emails.appointments.reminder', [
                'name' => $notifiable->name ?? '',
                'serviceName' => $serviceName,
                'date' => $date,
                'time' => $time,
                'staffName' => $staffName,
                'actionUrl' => route('appointmenthistory', $notifiable->id),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
