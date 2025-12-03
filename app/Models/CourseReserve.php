<?php

namespace App\Models;

use App\Enums\CourseReserve\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseReserve extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_register_id',
        'clue_id',
        'profession_id',
        'secretary_id',
        'paid_amount',
        'description',
        'status',
        'created_by',
        'deleted_by',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function clue(): BelongsTo
    {
        return $this->belongsTo(Clue::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(Secretary::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function courseRegister(): BelongsTo
    {
        return $this->belongsTo(CourseRegister::class);
    }
}
