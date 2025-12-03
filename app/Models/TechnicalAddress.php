<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechnicalAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'address',
        'phone',
        'branch_id',
        'province_id',
        'is_active',
        'created_by',
        'deleted_by'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
