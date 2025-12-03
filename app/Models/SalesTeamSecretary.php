<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesTeamSecretary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sales_team_id',
        'secretary_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function secretary()
    {
        return $this->belongsTo(Secretary::class, 'secretary_id');
    }
}
