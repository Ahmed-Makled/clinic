<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'image',
        'address',
        'governorate',
        'city',
        'degree',
        'price',
        'rating',
        'waiting_time',
        'category_id',
        'created_at',
    ];

    public $timestamps = true;

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'doctor_category')
                    ->withTimestamps();
    }
}
