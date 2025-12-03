<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'session_date',
        'session_start_time',
        'session_end_time',
        'is_active',
        'created_by',
        'deleted_by',
        'branch_id',
    ];

    protected $casts = [
        'session_date' => 'date',
        'session_start_time' => 'time',
        'session_end_time' => 'time',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
