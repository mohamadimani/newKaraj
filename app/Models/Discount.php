<?php

namespace App\Models;

use App\Enums\Discount\AmountTypeEnum;
use App\Enums\Discount\DiscountTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'code',
        'amount',
        'amount_type',
        'minimum_order_amount',
        'discount_type',
        'available_from',
        'available_until',
        'is_active',
        'usage_limit',
        'created_by',
        'deleted_by',
        'user_id',
        'profession_id',
        'course_id',
        'is_online',
        'used_count',
        'banner',
    ];

    protected $casts = [
        'available_until' => 'datetime',
        'discount_type' => DiscountTypeEnum::class,
        'amount_type' => AmountTypeEnum::class,
    ];

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
