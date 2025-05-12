<?php

namespace Modules\Patients\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Entities\User;
use Modules\Appointments\Entities\Appointment;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'address',
        'medical_history',
        'emergency_contact',
        'blood_type',
        'allergies',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'status' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class)->select('appointments.*'); // This ensures we select from appointments table explicitly
    }

    // This accessor will help display the patient's name in views
    public function getNameAttribute()
    {
        return $this->user->name;
    }

    // Calculate patient's age based on date_of_birth
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }

        // Force correct calculation direction from date_of_birth to now
        // and cast to integer to remove decimal places
        return (int) $this->date_of_birth->diffInYears(\Carbon\Carbon::now(), false);
    }
}
