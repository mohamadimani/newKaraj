<?php

namespace App\Models;

use App\Enums\CourseRegister\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseRegister extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'course_id',
        'internal_branch_id',
        'secretary_id',
        'amount',
        'paid_amount',
        'description',
        'is_paid',
        'is_active',
        'is_first_register',
        'created_by',
        'deleted_by',
        'status',
        'cancel_description',
        'reserve_description',
        'get_pack',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
        'status' => StatusEnum::class,
        'is_first_register' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(Secretary::class);
    }

    public function internalBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'internal_branch_id');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function technicals(): HasOne
    {
        return $this->hasOne(Technical::class, 'course_register_id', 'id');
    }

    public function debt(): int
    {
        return $this->amount > 0 ? ($this->amount - $this->paid_amount) : ($this->course->price - $this->paid_amount);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function profession(): BelongsTo
    {
        return $this->course->profession();
    }
}
