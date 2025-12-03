<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'public_price',
        'private_price',
        'public_capacity',
        'private_capacity',
        'public_duration_hours',
        'private_duration_hours',
        'created_by',
        'deleted_by'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class);
    }

    public function branchIds(): array
    {
        return $this->branches()->pluck('id')->toArray();
    }

    public function classRooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class)->where('is_active', true);
    }

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class)->where('is_active', true);
    }
}
