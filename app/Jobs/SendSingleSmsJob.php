<?php

namespace App\Jobs;

use App\Models\SendSmsLog;
use App\Models\User;
use App\Services\Messages\SMS\SMSService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendSingleSmsJob
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private $receiverUser,
        private string $textMessage
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            sendMessage($this->receiverUser, $this->textMessage, 'kavehnegar');
        } catch (Exception $e) {
            dump($e->getMessage());
            Log::info($e->getMessage());
        }
    }
}
