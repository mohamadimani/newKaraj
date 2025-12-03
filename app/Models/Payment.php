<?php

namespace App\Models;

use App\Enums\Payment\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paid_amount',
        'status',
        'user_id',
        'paymentable_id',
        'paymentable_type',
        'payment_method_id',
        'branch_id',
        'pay_date',
        'description',
        'reject_description',
        'created_by',
        'updated_by',
        'deleted_by',
        'discount_id',
        'is_wallet_pay',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function duplicate($paymentableId, $paymentableType): self
    {
        $payment = $this->replicate();

        $payment->paymentable_id = $paymentableId;
        $payment->paymentable_type = $paymentableType;

        $payment->save();

        return $payment;
    }

    public function getPaymentableTypesAttribute(): string
    {
        return match ($this->paymentable_type) {
            CourseRegister::class => __('payments.type_course_register'),
            CourseReserve::class => __('payments.type_course_reserve'),
            Order::class => 'سفارش دوره آنلاین',
            Technical::class => 'فنی حرفه ای',
        };
    }

    public function getPaymentableTitleAttribute(): string
    {
        if (isset($this->paymentable?->orderItems)) {
            $orderItemsName = null;
            foreach ($this->paymentable?->orderItems as $orderItem) {
                if ($orderItemsName == null) {
                    $orderItemsName = $orderItemsName . $orderItem->onlineCourse->name;
                } else {
                    $orderItemsName = $orderItemsName . ' , <br>' . $orderItem->onlineCourse->name;
                }
            }
        }
        return match ($this->paymentable_type) {
            CourseRegister::class => $this->paymentable->course->title,
            CourseReserve::class => $this->paymentable->profession->title,
            Technical::class => $this->paymentable->course->title,
            Order::class => $orderItemsName ?? '---',
        };
    }

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function paymentImage(): HasOne
    {
        return $this->hasOne(PaymentImage::class);
    }
}
