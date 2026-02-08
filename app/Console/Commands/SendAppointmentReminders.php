<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send appointment reminder emails one day before';

    public function handle(): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $appointments = Appointment::with(['service', 'staff.user', 'patientRecord.customer.user'])
            ->whereDate('date', $tomorrow)
            ->where('status', 'upcoming')
            ->get();

        foreach ($appointments as $appointment) {
            $user = $appointment->patientRecord?->customer?->user;
            if ($user) {
                $user->notify(new AppointmentReminder($appointment));
            }
        }

        $this->info('Appointment reminders sent: ' . $appointments->count());
        return Command::SUCCESS;
    }
}
