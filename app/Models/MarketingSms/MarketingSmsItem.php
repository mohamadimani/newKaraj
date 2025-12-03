<?php

namespace App\Models\MarketingSms;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingSmsItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'marketing_sms_template_id',
        'after_time',
        'content',
        'include_params',
        'after_time_details',
        'is_active',
        'created_by',
        'deleted_by'
    ];

    protected $casts = [
        'include_params' => AsArrayObject::class,
        'after_time_details' => AsArrayObject::class,
        'is_active' => 'boolean',
    ];

    /**
     * @return BelongsTo<MarketingSmsTemplate>
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(MarketingSmsTemplate::class, 'marketing_sms_template_id', 'id');
    }
}
