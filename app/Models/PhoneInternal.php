<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneInternal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'number',
        'phone_id',
        'secretary_id',
        'is_active',
        'created_by',
        'deleted_by'
    ];

    public function phone(): BelongsTo
    {
        return $this->belongsTo(Phone::class);
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(Secretary::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
