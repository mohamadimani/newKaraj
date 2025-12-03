<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'question_id',
        'answer',
        'is_correct',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function question()
    {
        return $this->hasOne(Question::class);
    }

    public function exam()
    {
        return $this->hasOne(Exam::class);
    }

}
