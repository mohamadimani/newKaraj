<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function onlineCourses()
    {
        return $this->hasMany(OnlineCourse::class, 'category_id');
    }
}
