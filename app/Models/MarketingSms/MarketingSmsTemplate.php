<?php

namespace App\Models\MarketingSms;

use App\Enums\MarketingSms\TargetTypeEnum;
use App\Models\Branch;
use App\Models\Profession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class MarketingSmsTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'profession_id',
        'branch_id',
        'target_type',
        'is_active',
        'created_by',
        'deleted_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'target_type' => TargetTypeEnum::class,
    ];

    public static function createObject(array $data): MarketingSmsTemplate
    {
        $data['created_by'] = Auth::id();

        $professions = $data['profession_ids'];
        unset($data['profession_ids']);

        $marketingSmsTemplate = self::create($data);
        $marketingSmsTemplate->professions()->sync($professions);

        return $marketingSmsTemplate->refresh();
    }

    public static function updateObject(MarketingSmsTemplate $marketingSmsTemplate, array $data): MarketingSmsTemplate
    {
        $data['created_by'] = Auth::id();

        $professions = $data['profession_ids'];
        unset($data['profession_ids']);

        $marketingSmsTemplate->update($data);
        $marketingSmsTemplate->professions()->sync($professions);

        return $marketingSmsTemplate->refresh();
    }

    /**
     * @return BelongsTo<Branch>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return HasMany<MarketingSmsItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(MarketingSmsItem::class);
    }

    /**
     * @return BelongsToMany<Profession>
     */
    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class, 'marketing_sms_template_profession', 'template_id', 'profession_id');
    }
}
