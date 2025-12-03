<?php

namespace App\Models;

use App\Enums\CourseRegister\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_register_id',
        'comment',
        'star',
        'q_1',
        'q_1_comment',
        'q_2',
        'q_2_comment',
        'q_3',
        'q_3_comment',
        'q_4',
        'q_4_comment',
        'yes_no_q_1',
        'yes_no_q_2',
        'yes_no_q_3',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courseRegister(): BelongsTo
    {
        return $this->belongsTo(CourseRegister::class);
    }

}
