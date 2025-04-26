<?php

namespace App\Models;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasStatus;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    const STATUS_COLUMN = 'status';
    const STATUSES = ['active', 'inactive'];
    const STATUS_LABELS = [
        'active' => 'نشط',
        'inactive' => 'غير نشط'
    ];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_category')
                    ->withTimestamps();
    }
}
