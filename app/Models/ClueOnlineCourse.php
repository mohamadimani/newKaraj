<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClueOnlineCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'clue_id',
        'online_course_id',
        'order_id',
        'order_item_id',
        'created_by',
    ];

    public function clue()
    {
        return $this->belongsTo(Clue::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function onlineCourse()
    {
        return $this->belongsTo(OnlineCourse::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
