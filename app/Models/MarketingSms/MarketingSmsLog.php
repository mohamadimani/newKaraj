<?php

namespace App\Models\MarketingSms;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingSmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketing_sms_item_id',
        'user_id',
        'mobile',
        'is_sent',
        'message'
    ];

    protected $casts = [
        'is_sent' => 'boolean',
    ];

    /**
     * @return BelongsTo<MarketingSmsItem>
     */
    public function marketingSmsItem(): BelongsTo
    {
        return $this->belongsTo(MarketingSmsItem::class);
    }

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
