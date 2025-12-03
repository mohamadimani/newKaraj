<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'site',
        'bank_card_name',
        'bank_card_number',
        'bank_card_owner',
        'manager',
        'minimum_pay',
        'online_pay_link',
        'is_active',
        'created_by',
        'deleted_by',
        'province_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function phones()
    {
        return $this->hasMany(Phone::class, 'branch_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
