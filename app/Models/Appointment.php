<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';
    protected $fillable = [
        'date',
        'time',
        'status',
        'service_id',
        'staff_id',
    ];

  public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staffID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function patientRecord()
    {
        return $this->hasOne(PatientRecord::class, 'appointment_id', 'id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'appointment_id', 'id');
    }
}
