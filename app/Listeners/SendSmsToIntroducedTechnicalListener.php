<?php

namespace App\Listeners;

use App\Events\TechnicalUpdatedToIntroduced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Email;

class SendSmsToIntroducedTechnicalListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TechnicalUpdatedToIntroduced $event): void
    {
        $user = $event->technical->user;
        $name = $event->technical->user->full_name;
        $text = "$name عزیز اطلاعات شما در سازمان فنی حرفه ای ثبت شد" . "\n آموزشگاه دنیز";

        sendMessage($user, $text, 'kavehnegar');
    }
}
