<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class AppointmentBooked extends Notification
{
    protected $appointment;
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::info('masuk');
         if ($notifiable instanceof \App\Models\Staff) {
            // Message for staff
            return (new MailMessage)
                ->subject('New Appointment Assigned')
                ->greeting('Hello ' . $notifiable->name)
                ->line('You have been assigned a new appointment.')
                ->line('Date: ' . $this->appointment->date)
                ->line('Time: ' . $this->appointment->time)
                ->line('Service: ' . $this->appointment->service->name)
                ->line('Thank you for your dedication!');
        } else {

            return (new MailMessage)
                ->subject('Appointment Confirmation')
                ->greeting('Hello ' . $notifiable->name)
                ->line('Your appointment has been successfully booked.')
                ->line('Date: ' . $this->appointment->date)
                ->line('Time: ' . $this->appointment->time)
                ->line('Service: ' . $this->appointment->service->name)
                ->line('See you soon!');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
