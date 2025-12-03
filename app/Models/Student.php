<?php

namespace App\Models;

use App\Enums\CourseRegister\StatusEnum as CourseRegisterStatusEnum;
use App\Enums\Payment\StatusEnum;
use App\Enums\Student\EducationEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'father_name',
        'national_code',
        'birth_certificate_number',
        'military_status',
        'education',
        'birth_place',
        'personal_image',
        'birth_certificate_image',
        'id_card_image',
        'created_by',
        'deleted_by',
        'marital_status',
    ];

    protected $casts = [
        'education' => EducationEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->courseRegisters();
    }

    public function courseRegisters(): HasMany
    {
        return $this->hasMany(CourseRegister::class)->whereIn('status', [CourseRegisterStatusEnum::REGISTERED, CourseRegisterStatusEnum::TECHNICAL]);
    }

    public function coursePayments()
    {
        return Payment::whereIn('paymentable_id', $this->courseRegisters()->pluck('id'))
            ->where('paymentable_type', CourseRegister::class);
    }

    public function totalPayments(): int
    {
        return $this->coursePayments()->where('status', StatusEnum::VERIFIED)->sum('paid_amount');
    }

    public function totalDebts(): int
    {
        $courseRegistersPricesAmount = $this->courseRegisters()->with('course')->get()->sum(function ($courseRegister) {
            $amount = $courseRegister->amount > 0 ? $courseRegister->amount : $courseRegister->course->price;
            return $amount;
        });

        return $courseRegistersPricesAmount - $this->totalPayments();
    }
}
