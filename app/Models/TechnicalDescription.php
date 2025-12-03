<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalDescription extends Model
{
    protected $fillable = [
        'technical_id',
        'description',
        'created_by',
    ];

    public function technical()
    {
        return $this->belongsTo(Technical::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(user::class, 'created_by', 'id');
    }

    public function getCreatedAtAttribute($value): string
    {
        return georgianToJalali($value, true, '/');
    }
}
