<?php

namespace App\Models;

use App\Enums\CourseRegister\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Secretary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'start_date',
        'leaving_date',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'leaving_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function phoneInternals(): HasMany
    {
        return $this->hasMany(PhoneInternal::class);
    }

    public function branchIds(): array
    {
        return $this->phoneInternals()
            ->join('phones', 'phone_internals.phone_id', '=', 'phones.id')
            ->pluck('phones.branch_id')
            ->unique()
            ->values()
            ->toArray();
    }

    public function getFullNameAttribute()
    {
        return $this->user->first_name ? $this->user->first_name . ' ' . $this->user->last_name : '';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function lastMonthSales()
    {
        $courseRegister = CourseRegister::where('status', StatusEnum::REGISTERED)->whereBetween('created_at', [verta()->startMonth()->toCarbon(), verta()->endMonth()->toCarbon()])->where('secretary_id', $this->id)->count();
        $reserves = CourseReserve::where('status', 'pending')->whereBetween('created_at', [verta()->startMonth()->toCarbon(), verta()->endMonth()->toCarbon()])->where('secretary_id', $this->id)->count();
        $onlineCourseOrders = Order::whereHas('onlinePayments', function ($query) {
            $query->where('status', 'paid')->where('is_active', true)->where('pay_confirm', true)->where('created_by', $this->user->id);
        })->where('is_active', true)->whereBetween('created_at', [verta()->startMonth()->toCarbon(), verta()->endMonth()->toCarbon()])->where('created_by', $this->user->id)->count();
        return $courseRegister + $reserves + $onlineCourseOrders;
    }

    public function todaySales()
    {
        $courseRegister = CourseRegister::where('status', StatusEnum::REGISTERED)->whereBetween('created_at', [verta()->startDay()->toCarbon(), verta()->endDay()->toCarbon()])->where('secretary_id', $this->id)->count();
        $reserves = CourseReserve::where('status', 'pending')->whereBetween('created_at', [verta()->startDay()->toCarbon(), verta()->endDay()->toCarbon()])->where('secretary_id', $this->id)->count();
        $onlineCourseOrders = Order::whereHas('onlinePayments', function ($query) {
            $query->where('status', 'paid')->where('is_active', true)->where('pay_confirm', true)->where('created_by', $this->user->id);
        })->where('is_active', true)->whereBetween('created_at', [verta()->startDay()->toCarbon(), verta()->endDay()->toCarbon()])->where('created_by', $this->user->id)->count();
        return $courseRegister + $reserves + $onlineCourseOrders;
    }
}
