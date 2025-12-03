<?php

namespace App\Models;

use App\Enums\Technical\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'user_id',
        'online_course_id',
        'amount',
        'quantity',
        'total_amount',
        'discount_id',
        'discount_amount',
        'final_amount',
        'is_active',
        'deleted_by',
        'created_by',
        'teacher_id',
        'teacher_percent',
        'teacher_withdraw',
        'teacher_withdraw_date',
        'spot_key',
        'license_key',
        'license_url',
        'license_id',
        'pay_date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function onlineCourse()
    {
        return $this->belongsTo(OnlineCourse::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopePaid($query)
    {
        return $query->where('license_key', '!=', null);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasTechnical()
    {
        $technical = Technical::where('user_id', $this->user_id)
            ->whereIn('status', [StatusEnum::PROCESSING->value, StatusEnum::INTRODUCED->value])
            ->where('course_register_id', $this->id)
            ->where('course_id', $this->online_course_id)->first();
        return $technical;
    }
}
