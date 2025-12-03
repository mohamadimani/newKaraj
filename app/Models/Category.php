<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'parent_id',
        'is_active',
        'is_active_by',
        'inactive_by',
        'deleted_by',
        'created_by',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}
