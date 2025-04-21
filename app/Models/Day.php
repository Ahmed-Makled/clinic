<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Day extends Model
{
    use HasFactory;

    protected $table = 'days';

    protected $fillable = [
        'equivalent',
        'name_ar',
        'name_en',
    ];

    public $timestamps = false;
}
