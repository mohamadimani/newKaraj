<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineCourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'duration_hour',
        'is_active',
        'registered_count',
        'spot_key',
        'discount_amount',
        'discount_start_at',
        'discount_expire_at',
        'image',
        'deleted_by',
        'created_by',
        'discount_start_at_jalali',
        'discount_expire_at_jalali',
        'category_id',
        'teacher_id',
        'percent',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function onlineCourseGroup()
    {
        return $this->belongsTo(OnlineCourseGroup::class, 'category_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
