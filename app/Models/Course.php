<?php

namespace App\Models;

use App\Enums\Course\CourseTypeEnum;
use App\Observers\CourseObserver;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'week_days' => 'array',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $fillable = [
        'title',
        'capacity',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'price',
        'week_days',
        'duration_hours',
        'profession_id',
        'teacher_id',
        'branch_id',
        'class_room_id',
        'is_active',
        'course_type',
        'created_by',
        'deleted_by',
        'teacher_withdraw_at',
        'teacher_withdraw_by',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function boot()
    {
        parent::boot();
        self::observe([CourseObserver::class]);
    }

    public static function createObject(array $data): Course
    {
        return self::create($data);
    }

    public static function updateObject(Course $course, array $data): Course
    {
        $course->update($data);
        return $course;
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function courseSessions(): HasMany
    {
        return $this->hasMany(CourseSession::class);
    }

    public function courseRegisters(): HasMany
    {
        return $this->hasMany(CourseRegister::class)->whereNotIn('status', ['cancelled', 'reserved']);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function coursePricesLogs(): HasMany
    {
        return $this->hasMany(CoursePricesLog::class);
    }

    public function updateSessions()
    {
        $sessionDates = self::calculateCourseSessionDates(
            $this->start_date,
            $this->end_date,
            $this->week_days,
            $this->start_time,
            $this->end_time,
        );

        CourseSession::where('course_id', $this->id)->delete();
        if (count($sessionDates) > 0) {
            $sessionDates = array_map(function ($sessionDate) {
                $sessionDate['course_id'] = $this->id;
                $sessionDate['created_by'] = Auth::id();
                $sessionDate['created_at'] = now();
                $sessionDate['updated_at'] = now();
                return $sessionDate;
            }, $sessionDates);

            CourseSession::insert($sessionDates);
        }
    }

    public function generateSessions()
    {
        $sessionDates = self::calculateCourseSessionDates(
            $this->start_date,
            $this->end_date,
            $this->week_days,
            $this->start_time,
            $this->end_time,
        );
        if (count($sessionDates) > 0) {
            $sessionDates = array_map(function ($sessionDate) {
                $sessionDate['course_id'] = $this->id;
                $sessionDate['created_by'] = Auth::id();
                $sessionDate['created_at'] = now();
                $sessionDate['updated_at'] = now();
                return $sessionDate;
            }, $sessionDates);

            CourseSession::insert($sessionDates);
        }
    }

    public static function calculateCourseSessionDates(
        string $startDate,
        string $endDate,
        array $weekDays,
        string $startTime,
        string $endTime,
    ): array {
        $startDate = Verta::instance($startDate);
        $endDate = Verta::instance($endDate);
        $sessionDates = [];
        $currentDate = $startDate;
        while ($currentDate->lessThanOrEqualTo($endDate)) {
            $dayNumber = $currentDate->dayOfWeek;
            if (in_array($dayNumber, $weekDays)) {
                $sessionDates[] = [
                    'session_date' => toGeorgianDate($currentDate->format('Y-m-d')),
                    'session_start_time' => $startTime,
                    'session_end_time' => $endTime,
                ];
            }

            $currentDate->addDay();
        }
        return $sessionDates;
    }

    public function createPriceLog()
    {
        $lastCoursePricesLog = $this->coursePricesLogs()?->first();
        if ($lastCoursePricesLog) {
            $lastCoursePricesLog->deleted_by = Auth::id();
            $lastCoursePricesLog->save();
            $lastCoursePricesLog->delete();
        }

        CoursePricesLog::create([
            'course_id' => $this->id,
            'price' => $this->price,
            'created_by' => Auth::id(),
        ]);
    }

    public function paidAmountSum()
    {
        return $this->courseRegisters()->sum('paid_amount');
    }

    public function remainingAmount()
    {
        $courseAmount = 0;

        foreach ($this->courseRegisters()->get() as $courseRegister) {
            $courseAmount += $courseRegister->amount > 0  ? $courseRegister->amount : $this->price;
        }
        return $courseAmount - $this->courseRegisters()->sum('paid_amount');
    }

    public static function weekDays(): array
    {
        return [
            ['id' => 0, 'name' => __('public.saturday')],
            ['id' => 1, 'name' => __('public.sunday')],
            ['id' => 2, 'name' => __('public.monday')],
            ['id' => 3, 'name' => __('public.tuesday')],
            ['id' => 4, 'name' => __('public.wednesday')],
            ['id' => 5, 'name' => __('public.thursday')],
            ['id' => 6, 'name' => __('public.friday')],
        ];
    }
}
