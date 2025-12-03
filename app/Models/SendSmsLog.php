<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SendSmsLog extends Model
{
    protected $fillable = [
        'user_id',
        'mobile',
        'is_sent',
        'message',
        'created_by'
    ];

    protected $casts = [
        'is_sent' => 'boolean',
    ];

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function setSmsLog($userId, $mobile, $message)
    {
        SendSmsLog::create([
            'user_id' => $userId,
            'mobile' => $mobile,
            'message' => $message,
            'is_sent' => true,
            'created_by' => auth()->id(),
        ]);
    }
}
