<?php

namespace App\Console\Commands;

use App\Jobs\SendOnlineMarketingSmsJob;
use App\Models\Clue;
use App\Models\ClueOnlineCourse;
use App\Models\OnlineMarketingSms;
use App\Models\OnlineMarketingSmsLog;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class FindOnlineMarketingSmsTargetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:find-online-marketing-sms-target-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to find online marketing sms targets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $onlineMarketingSms = OnlineMarketingSms::get();

        foreach ($onlineMarketingSms as $sms) {
            $clueOnlineCourses = ClueOnlineCourse::where('online_course_id', $sms->online_course_id)
                ->where('created_at', '<=', Carbon::now()->subSeconds($sms->after_time))
                ->get();

            foreach ($clueOnlineCourses as $clueOnlineCourse) {
                $alreadySent = OnlineMarketingSmsLog::query()
                    ->where('user_id', $clueOnlineCourse->clue->user->id)
                    ->where('online_marketing_sms_id', $sms->id)
                    ->where('target_type', $sms->target_type)
                    ->where('is_sent', true)
                    ->first();

                if (!$alreadySent) {
                    dispatch(new SendOnlineMarketingSmsJob($clueOnlineCourse->clue->user, $sms));
                }
            }
        }
    }
}
