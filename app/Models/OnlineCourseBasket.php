<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseBasket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'online_course_id',
        'quantity',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function onlineCourse()
    {
        return $this->belongsTo(OnlineCourse::class , 'online_course_id' , 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
