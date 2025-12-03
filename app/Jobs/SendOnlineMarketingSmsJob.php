<?php

namespace App\Jobs;

use App\Models\OnlineMarketingSms;
use App\Models\OnlineMarketingSmsLog;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendOnlineMarketingSmsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private User $user,
        private OnlineMarketingSms $onlineMarketingSms,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();
            sendMessage($this->user, $this->onlineMarketingSms->message, 'kavehnegar');
            OnlineMarketingSmsLog::query()->create([
                'user_id' => $this->user->id,
                'mobile' => $this->user->mobile,
                'online_marketing_sms_id' => $this->onlineMarketingSms->id,
                'message' => $this->onlineMarketingSms->message,
                'is_sent' => true,
                'target_type' => $this->onlineMarketingSms->target_type,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dump($e->getMessage());
            Log::info($e->getMessage());
        }
    }
}
