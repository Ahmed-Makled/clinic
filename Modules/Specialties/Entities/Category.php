<?php

namespace Modules\Specialties\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Traits\HasStatus;
use Modules\Doctors\Entities\Doctor;
use Modules\Appointments\Entities\Appointment;

class Category extends Model
{
    use HasFactory, HasStatus;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'status',
        'slug'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get all doctors that belong to this category
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    /**
     * Get all appointments through doctors that belong to this category
     */
    public function appointments()
    {
        return $this->hasManyThrough(
            Appointment::class,
            Doctor::class,
            'category_id', // Foreign key on doctors table
            'doctor_id',   // Foreign key on appointments table
            'id',          // Local key on categories table
            'id'           // Local key on doctors table
        );
    }
}
