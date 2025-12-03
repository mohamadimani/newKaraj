<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'is_active',
        'created_by',
        'deleted_by',
        'start_date',
        'leaving_date'
    ];

    protected $casts = ['start_date' => 'datetime', 'leaving_date' => 'datetime'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class);
    }

    public function professions()
    {
        return $this->belongsToMany(Profession::class);
    }

    public function branchIds(): array
    {
        return $this->branches()
            ->pluck('id')
            ->unique()
            ->values()
            ->toArray();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class)->whereNotNull('pay_date')->where('teacher_withdraw', false);
    }
}
