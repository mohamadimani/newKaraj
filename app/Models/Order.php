<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'total_amount',
        'discount_id',
        'discount_amount',
        'final_amount',
        'payment_id',
        'payment_status',
        'is_active',
        'pay_date',
        'created_by',
        'deleted_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function onlinePayments()
    {
        return $this->hasMany(OnlinePayment::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function debt(): int
    {
        return $this->final_amount - $this->onlinePayments->where('status', 'paid')->sum('paid_amount');
    }
}
