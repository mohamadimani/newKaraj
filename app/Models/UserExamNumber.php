<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserExamNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'course_register_id',
        'course_id',
        'profession_id',
        'exam_type',
        'exam_number',
        'description',
        'created_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function courseRegister(): BelongsTo
    {
        return $this->belongsTo(CourseRegister::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

}
