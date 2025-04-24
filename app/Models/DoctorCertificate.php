<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'title',
        'issuer',
        'issue_date',
        'expiry_date',
        'certificate_number',
        'file_path',
        'is_verified'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_verified' => 'boolean'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
