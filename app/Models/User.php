<?php

namespace App\Models;

use App\Models\MarketingSms\MarketingSmsLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Observers\UserObserver;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile',
        'mobile2',
        'phone',
        'national_code',
        'address',
        'postal_code',
        'avatar',
        'gender',
        'birth_date',
        'balance',
        'is_admin',
        'is_active',
        'created_by',
        'province_id',
        'wallet',
    ];


    protected static function boot()
    {
        parent::boot();
        self::observe(UserObserver::class);
    }

    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getBirthDateAttribute($value)
    {
        if ($value) {
            return georgianToJalali($value);
        }
    }

    public function getFullNameAttribute()
    {
        return $this->first_name ? $this->first_name . ' ' . $this->last_name : '';
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function clue(): HasOne
    {
        return $this->hasOne(Clue::class);
    }

    public function secretary(): HasOne
    {
        return $this->hasOne(Secretary::class);
    }

    public function isSecretary(): bool
    {
        return $this->secretary()->exists();
    }

    public function baskets(): HasMany
    {
        return $this->hasMany(OnlineCourseBasket::class);
    }

    public function courseBaskets(): HasMany
    {
        return $this->hasMany(CourseBasket::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function isTeacher(): bool
    {
        return $this->teacher()->exists();
    }

    public function clerk(): HasOne
    {
        return $this->hasOne(Clerk::class);
    }

    public function isClerk(): bool
    {
        return $this->clerk()->exists();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function marketingSmsLogs(): HasMany
    {
        return $this->hasMany(MarketingSmsLog::class);
    }
    public function onlineMarketingSmsLogs(): HasMany
    {
        return $this->hasMany(OnlineMarketingSmsLog::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    public function hasClue(): bool
    {
        return $this->clue()->exists();
    }

    public function sendSmsLogs(): HasMany
    {
        return $this->hasMany(SendSmsLog::class);
    }
}
