<?php

namespace App\Jobs;

use App\Models\MarketingSms\MarketingSmsItem;
use App\Models\MarketingSms\MarketingSmsLog;
use App\Models\User;
use App\Services\Messages\SMS\SMSService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendMarketingSmsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private User $user,
        private MarketingSmsItem $marketingSmsItem,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            $alreadySent = MarketingSmsLog::query()
                ->where('user_id', $this->user->id)
                ->where('marketing_sms_item_id', $this->marketingSmsItem->id)
                ->where('is_sent', true)
                ->first();

            if (!is_null($alreadySent)) {
                throw new Exception('Marketing SMS already sent to this user - UserID: ' . $this->user->id . ' MarketingSmsItemID: ' . $this->marketingSmsItem->id);
            }

            $studentName = isset($this->marketingSmsItem->include_params?->show_student_name) && $this->marketingSmsItem->include_params?->show_student_name
                ? ($this->user->fullName ? $this->user->fullName . ' عزیز' : '')
                : '';
            $secretaryName = isset($this->marketingSmsItem->include_params?->show_secretary_name) && $this->marketingSmsItem->include_params?->show_secretary_name
                ? ($this->user->clue?->secretary?->user?->fullName
                    ? 'مشاور شما: ' . $this->user->clue?->secretary?->user?->fullName
                    : '')
                : '';
            $branchName = isset($this->marketingSmsItem->include_params?->show_branch_name) ? 'آموزشگاه دنیز' : '';

            $finalMessage = $studentName . "\n" . $this->marketingSmsItem->content . "\n" . $secretaryName . "\n" . $branchName;

            // $smsService = new SMSService();
            // $smsService->setReceiver($this->user->mobile);
            // $smsService->setContent($finalMessage);
            // $smsService->sendMessage();

            sendMessage($this->user, $finalMessage, 'kavehnegar');

            MarketingSmsLog::query()->create([
                'user_id' => $this->user->id,
                'mobile' => $this->user->mobile,
                'marketing_sms_item_id' => $this->marketingSmsItem->id,
                'message' => $finalMessage,
                'is_sent' => true,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dump($e->getMessage());
            Log::info($e->getMessage());
        }
    }
}
