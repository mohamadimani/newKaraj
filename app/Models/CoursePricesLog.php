<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CoursePricesLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['course_id', 'price', 'created_by', 'deleted_by'];

    protected static function booted(): void
    {
        static::deleted(function (CoursePricesLog $coursePricesLog) {
            $coursePricesLog->deleted_by = Auth::id();
            $coursePricesLog->save();
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
