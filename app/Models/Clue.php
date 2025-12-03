<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clue extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'branch_id',
        'secretary_id',
        'familiarity_way_id',
        'is_active',
        'created_by',
        'deleted_by',
        'student_id',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function familiarityWay(): BelongsTo
    {
        return $this->belongsTo(FamiliarityWay::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(Secretary::class);
    }

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class)->withPivot(['course_register_id', 'created_at', 'updated_at']);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function createdBy(): ?string
    {
        return $this->created_by ? User::find($this->created_by)->fullName : null;
    }

    public function followUps(): HasMany
    {
        return $this->user->followUps;
    }
}
