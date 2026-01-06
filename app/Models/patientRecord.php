<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class patientRecord extends Model
{
    protected $table = 'patientRecord';
    protected $fillable = [
        'staff_id',
        'date',
        'time',
        'status',
        'service_id',
        'patient_id',
        'appointment_id',
        'visit_date', 
        'notes',
        'referral_letter'
    ];
    public function appointment()
{
    return $this->belongsTo(Appointment::class, 'appointment_id');
}
}
