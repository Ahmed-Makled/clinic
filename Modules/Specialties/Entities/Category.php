<?php

namespace Modules\Specialties\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Modules\Core\Traits\HasStatus;
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

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_category')
                    ->withTimestamps();
    }

    public function appointments()
    {
        return $this->hasManyThrough(
            Appointment::class,
            Doctor::class,
            null,
            'doctor_id'
        )->join('doctor_category', function($join) {
            $join->on('doctors.id', '=', 'doctor_category.doctor_id')
                 ->where('doctor_category.category_id', '=', $this->id);
        });
    }
}
