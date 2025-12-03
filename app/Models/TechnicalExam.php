<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechnicalExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_date',
        'exam_number',
        'exam_description',
        'exam_type',
        'technical_id',
        'technical_address_id',
    ];

    public function getExamDateAttribute($value)
    {
        if ($value) {
            if ($this->exam_type == 'written') {
                return georgianToJalali(date('Y-m-d', $value), false, '/');
            } else {
                return georgianToJalali(date('Y-m-d H:i:s', $value), true, '/');
            }
        }
        return null;
    }

    public function technical(): BelongsTo
    {
        return $this->belongsTo(Technical::class);
    }
}
