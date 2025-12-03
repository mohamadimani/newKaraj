<?php

namespace App\Models;

use Arcanedev\Support\Providers\Concerns\HasFactories;
use Illuminate\Database\Eloquent\Model;

class TransferWalletLog extends Model
{
    use HasFactories;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'amount',
        'description',
        'created_by',
    ];

    public static function setLog($fromUserId, $toUserId, $amount, $description)
    {
        TransferWalletLog::create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'amount' => $amount,
            'description' => $description,
            'created_by' => auth()->id(),
        ]);
    }

    public function toUser()
    {
        return $this->belongsTo(User::class , 'to_user_id');
    }
}
