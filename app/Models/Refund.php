<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'user_id',
        'course_id',
        'course_register_id',
        'description',
        'confirmed_by',
        'confirmed_at',
        'is_online',
        'is_active',
        'created_by',
        'final_confirmed_by',
        'final_confirmed_at',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function courseRegister(): BelongsTo
    {
        return $this->belongsTo(CourseRegister::class);
    }
}
