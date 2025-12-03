<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderItemChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'field_name_en',
        'field_name_fa',
        'previous_value',
        'new_value',
        'description',
        'branch_id',
        'created_by',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return georgianToJalali($value, true);
    }

    public static function addLog($orderItem, $fieldNameEn, $fieldNameFa, $previousValue, $newValue, $description = null)
    {
        if (!$orderItem or empty($orderItem->id)) {
            throw new \InvalidArgumentException('شناسه سفارش نمی‌تواند خالی باشد');
        }

        if (empty($fieldNameEn)) {
            throw new \InvalidArgumentException('نام فیلد نمی‌تواند خالی باشد');
        }

        if (!OrderItem::find($orderItem->id)) {
            throw new \InvalidArgumentException('شناسه سفارش نامعتبر است');
        }
        return self::create([
            'order_item_id' => $orderItem->id,
            'field_name_en' => $fieldNameEn,
            'field_name_fa' => $fieldNameFa,
            'previous_value' => $previousValue,
            'new_value' => $newValue,
            'branch_id' => $orderItem->branch_id,
            'description' => $description ?? null,
            'created_by' => Auth::id(),
        ]);
    }
}

