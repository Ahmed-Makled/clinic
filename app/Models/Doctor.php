<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'bio',
        'image',
        'title',
        'price',
        'experience_years',
        'address',
        'governorate',
        'city',
        'degree',
        'rating',
        'waiting_time'
    ];

    public $timestamps = true;

    /**
     * Get the user that the doctor belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the doctor's name from the associated user.
     */
    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : null;
    }

    /**
     * Get the doctor's email from the associated user.
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }

    /**
     * Get the doctor's phone from the associated user.
     */
    public function getPhoneAttribute()
    {
        return $this->user ? $this->user->phone_number : null;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'doctor_category')
                    ->withTimestamps();
    }

}
