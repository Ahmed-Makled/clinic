<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Traits\Searchable;

class Doctor extends Model
{
    use HasFactory, Searchable;

    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'name',
        'bio',
        'description',
        'image',
        'governorate_id', // تحديث من governorate إلى governorate_id
        'city_id', // تحديث من city إلى city_id
        'address',
        'degree',
        'price',
        'rating',
        'waiting_time',
        'consultation_fee',
        'experience_years',
        'gender',
        'status'
    ];

    protected $searchable = [
        'name',
        'email',
        'phone',
        'bio',
        'description'
    ];

    public $timestamps = true;

    /**
     * Handle image upload and storage
     */
    public static function uploadImage($image)
    {
        if ($image) {
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            return $image->storeAs('doctors', $imageName, 'public');
        }
        return null;
    }

    /**
     * Delete the doctor's image from storage
     */
    public function deleteImage()
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
        }
    }

    /**
     * Get the image URL attribute
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }
        return asset('images/default-doctor.png');
    }

    /**
     * Get the user that the doctor belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the doctor's name by combining first and last name.
     */
    public function getNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return trim($this->first_name . ' ' . $this->last_name);
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

    /**
     * Get the governorate that the doctor belongs to.
     */
    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    /**
     * Get the city that the doctor belongs to.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get all appointments for the doctor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}
