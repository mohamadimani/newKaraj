<?php

namespace App\Models;

use App\Enums\Technical\StatusEnum;
use App\Observers\TechnicalObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Technical extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'is_active',
        'created_by',
        'deleted_by',
        'course_register_id',
        'course_id',
        'student_id',
        'paid_amount',
        'amount_descreption',
        'branch_id',
        'is_online_course',
    ];

    public static function boot()
    {
        parent::boot();
        self::observe([TechnicalObserver::class]);
    }

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function getIsActiveAttribute($value): string
    {
        return $value == true ? '<span class="badge bg-label-success me-1">فعال</span>' : '<span class="badge bg-label-danger me-1">غیر فعال</span>';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getPaidAmountAttribute($value): string
    {
        return number_format($value);
    }

    public function getCreatedAtAttribute($value): string
    {
        return georgianToJalali($value, true);
    }

    public function getUpdatedAtAttribute($value): string
    {
        return georgianToJalali($value, true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function courseRegister(): BelongsTo
    {
        return $this->belongsTo(CourseRegister::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function onlineCourse(): BelongsTo
    {
        return $this->belongsTo(OnlineCourse::class, 'course_id', 'id');
    }

    public function technicalExams(): HasMany
    {
        return $this->hasMany(TechnicalExam::class);
    }

    public function writtenExams(): HasMany
    {
        return $this->hasMany(TechnicalExam::class)->where('exam_type', 'written');
    }

    public function practicalExams(): HasMany
    {
        return $this->hasMany(TechnicalExam::class)->where('exam_type', 'practical');
    }

    public function technicalDescriptions(): HasMany
    {
        return $this->hasMany(TechnicalDescription::class);
    }
}
