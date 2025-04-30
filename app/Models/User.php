<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

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

    /**
     * Get the doctor record associated with the user.
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Get the patient record associated with the user.
     */
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Get all appointments for this user through their patient record.
     */
    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Patient::class);
    }

    /**
     * Check if the user is a patient.
     */
    public function isPatient()
    {
        return $this->hasRole('Patient');
    }

    /**
     * Get the patient record if this user is a patient.
     */
    public function getPatientRecord()
    {
        return $this->isPatient() ? $this->patient : null;
    }

    /**
     * Check if the user is a doctor.
     */
    public function isDoctor()
    {
        return $this->hasRole('Doctor');
    }

    /**
     * Get the doctor record if this user is a doctor.
     */
    public function getDoctorRecord()
    {
        return $this->isDoctor() ? $this->doctor : null;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }
}
