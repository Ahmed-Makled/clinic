<?php

namespace App\Models;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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

    const STATUS_COLUMN = 'status';
    const STATUSES = ['active', 'inactive'];
    const STATUS_LABELS = [
        'active' => 'نشط',
        'inactive' => 'غير نشط'
    ];

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
}
