<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'name',
        'number',
        'capacity',
        'is_active',
        'created_by',
        'deleted_by'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class);
    }
}
