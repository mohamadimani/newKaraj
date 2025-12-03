<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'title',
        'description',
        'is_active',
        'create_by'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'create_by');
    }
}
