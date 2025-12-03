<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamiliarityWay extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'title',
        'slug',
        'sort',
        'is_active',
        'created_by',
        'deleted_by',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
