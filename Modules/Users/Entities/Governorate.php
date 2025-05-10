<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Governorate extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
