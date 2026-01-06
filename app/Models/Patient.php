<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
    'name',
    'email',
    'gender',
    'age',
    'contact_number',
    'ic_number',
    'student_id',
    'patient_type',
    'user_id', 
    'place_of_injury',
    'reason_of_visit',
    'type_of_injury'
];
}
