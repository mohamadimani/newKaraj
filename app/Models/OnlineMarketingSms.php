<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineMarketingSms extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'online_course_id',
        'target_type',
        'after_time',
        'message',
        'is_active',
        'created_by',
        'deleted_by',
    ];

    public function afterSeconds()
    {
        return $this->after_time;
    }

    public function afterMinutes()
    {
        return ($this->after_time / 60);
    }

    public function afterHours()
    {
        return ($this->after_time / 3600);
    }

    public function afterDays()
    {
        return ($this->after_time / 86400);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function onlineCourse()
    {
        return $this->belongsTo(OnlineCourse::class, 'online_course_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
