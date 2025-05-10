<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = [
        'name',
        'governorate_id'
    ];

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }
}
