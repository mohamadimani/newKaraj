<?php

namespace App\Models;

use App\Enums\Payment\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'field_name_en',
        'field_name_fa',
        'previous_value',
        'new_value',
        'branch_id',
        'description',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPaymentAmount()
    {
        return $this->payment->paid_amount ?? '';
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public static function addLog($payment, $fieldNameEn, $fieldNameFa, $previousValue, $newValue, $description = null)
    {
        if (!$payment or empty($payment->id)) {
            throw new \InvalidArgumentException('شناسه پرداخت نمی‌تواند خالی باشد');
        }

        if (empty($fieldNameEn)) {
            throw new \InvalidArgumentException('نام فیلد نمی‌تواند خالی باشد');
        }

        if (!Payment::find($payment->id)) {
            throw new \InvalidArgumentException('شناسه پرداخت نامعتبر است');
        }
        return self::create([
            'payment_id' => $payment->id,
            'field_name_en' => $fieldNameEn,
            'field_name_fa' => $fieldNameFa,
            'previous_value' => $previousValue,
            'new_value' => $newValue,
            'branch_id' => $payment->branch_id,
            'description' => $description ?? null,
            'created_by' => Auth::id(),
        ]);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPreviousValueAttribute($value)
    {
        if ($this->field_name_en === 'payment_method_id') {
            return PaymentMethod::find($value)->title;
        } elseif ($this->field_name_en === 'paid_amount') {
            return number_format($value);
        } elseif ($this->field_name_en === 'status') {
            return StatusEnum::getLabelForLog($value);
        }
        return $value;
    }

    public function getNewValueAttribute($value)
    {
        if ($this->field_name_en === 'payment_method_id') {
            return PaymentMethod::find($value)->title;
        } elseif ($this->field_name_en === 'paid_amount') {
            return number_format(unformatNumber($value));
        } elseif ($this->field_name_en === 'status') {
            return StatusEnum::getLabelForLog($value);
        }
        return $value;
    }
}
