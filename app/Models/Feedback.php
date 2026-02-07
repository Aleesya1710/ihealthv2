<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';
    protected $fillable = [
        'message',
        'rating',
        'appointment_id',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
