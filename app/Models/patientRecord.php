<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    use HasFactory;

    protected $table = 'patientRecord';

    protected $fillable = [
        'place_of_injury',
        'symptoms',
        'type_of_injury',
        'diagnosis',
        'treatment',
        'notes',
        'referral_letter',
        'customer_id',
        'appointment_id',
    ];

    protected $casts = [
        'symptoms' => 'array',
        'type_of_injury' => 'array',
        'diagnosis' => 'array',
        'treatment' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
