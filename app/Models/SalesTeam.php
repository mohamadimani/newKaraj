<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesTeam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'sales_team_manager_id',
        'monthly_sale_target',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
        'branch_id'
    ];

    public function secretaries()
    {
        return $this->hasMany(SalesTeamSecretary::class);
    }

    public function salesTeamManager()
    {
        return $this->belongsTo(Secretary::class, 'sales_team_manager_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
