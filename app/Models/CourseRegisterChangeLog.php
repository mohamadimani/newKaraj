<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRegisterChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_register_id',
        'field_name',
        'previous_value',
        'new_value',
        'user_id',
        'branch_id',
        'description',
        'course_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStudentFullName()
    {
        return $this->courseRegister->student->user->full_name ?? '';
    }

    public function courseRegister()
    {
        return $this->belongsTo(CourseRegister::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public static function addLog($courseRegister, $fieldName, $previousValue, $newValue, $description = null)
    {
        if (!$courseRegister or empty($courseRegister->id)) {
            throw new \InvalidArgumentException('شناسه ثبت نام دوره نمی‌تواند خالی باشد');
        }

        if (empty($fieldName)) {
            throw new \InvalidArgumentException('نام فیلد نمی‌تواند خالی باشد');
        }

        if (!CourseRegister::find($courseRegister->id)) {
            throw new \InvalidArgumentException('شناسه ثبت نام دوره نامعتبر است');
        }
        return self::create([
            'course_register_id' => $courseRegister->id,
            'field_name' => $fieldName,
            'previous_value' => $previousValue,
            'new_value' => $newValue,
            'user_id' => auth()->id(),
            'branch_id' => $courseRegister->internal_branch_id,
            'description' => $description ?? null,
            'course_id' => $courseRegister->course_id,
        ]);
    }
}
