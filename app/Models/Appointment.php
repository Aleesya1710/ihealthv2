<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'staff_id',
        'date',
        'time',
        'status',
        'service_id',
        'patient_id',
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id'); 
    }
    public function patientrecord(){
        return $this->belongsTo(Service::class);
    }
    public function feedback()
{
    return $this->hasOne(Feedback::class);
}
 public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
  

}
