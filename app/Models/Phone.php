<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'number',
        'parent_id',
        'is_active',
        'created_by',
        'deleted_by',
        'branch_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function secretaries(): BelongsToMany
    {
        return $this->belongsToMany(Secretary::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHasActiveParent($query)
    {
        return $query->whereHas('parent', function ($query) {
            $query->where('is_active', true);
        });
    }

    public function scopeBranchIsActive($query)
    {
        return $query->whereHas('branch', function ($query) {
            $query->where('is_active', true);
        });
    }

    public function scopeActiveInternals($query)
    {
        return $query->active()->hasActiveParent()->branchIsActive();
    }

}
