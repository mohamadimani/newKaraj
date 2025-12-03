<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupDescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['description'];

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, 'group_description_profession')->withPivot('sort')->orderBy('sort');
    }

    public function professionCourses(): Collection
    {
        return Course::query()->whereIn('profession_id', $this->professions->pluck('id')->toArray())->where('start_date', '>=', now()->subDay())->orderBy('start_date', 'asc')->get();
    }
}
