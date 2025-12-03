<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goods extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'branch_id',
        'class_room_id',
        'count',
        'health_status',
        'description',
        'image',
        'is_active',
        'created_by',
        'deleted_by',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function reports()
    {
        return $this->hasMany(GoodsReport::class);
    }
}
