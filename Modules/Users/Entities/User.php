<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Modules\Doctors\Entities\Doctor;
use Modules\Patients\Entities\Patient;
use Modules\Appointments\Entities\Appointment;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'status',
        'last_seen'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen' => 'datetime',
            'status' => 'boolean'
        ];
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Patient::class);
    }

    public function isPatient()
    {
        return $this->hasRole('Patient');
    }

    public function getPatientRecord()
    {
        return $this->isPatient() ? $this->patient : null;
    }

    public function isDoctor()
    {
        return $this->hasRole('Doctor');
    }

    public function getDoctorRecord()
    {
        return $this->isDoctor() ? $this->doctor : null;
    }

    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }
}
