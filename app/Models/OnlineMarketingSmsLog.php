<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlineMarketingSmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'online_marketing_sms_id',
        'user_id',
        'target_type',
        'mobile',
        'is_sent',
        'message',
    ];

    public function onlineMarketingSms(): BelongsTo
    {
        return $this->belongsTo(OnlineMarketingSms::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
