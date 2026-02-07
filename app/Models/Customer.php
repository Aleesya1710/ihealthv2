<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'ICNumber',
        'studentID',
        'faculty',
        'phoneNumber',
        'program',
        'staffID',
        'category',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patientRecords()
    {
        return $this->hasMany(PatientRecord::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
