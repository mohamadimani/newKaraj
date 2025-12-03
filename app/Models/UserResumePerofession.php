<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResumePerofession extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'profession_id',
        'persent',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function profession()
    {
        return $this->hasOne(Profession::class,'id' , 'profession_id');
    }
}
