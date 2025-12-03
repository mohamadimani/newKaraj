<?php

namespace App\Console\Commands;

use App\Enums\MarketingSms\TargetTypeEnum;
use App\Jobs\SendMarketingSmsJob;
use App\Models\Clue;
use App\Models\MarketingSms\MarketingSmsItem;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class FindMarketingSmsTargetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:find-marketing-sms-targets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to find marketing sms targets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $marketingSmsItems = MarketingSmsItem::query()->with(['template', 'template.professions'])->get();

        foreach ($marketingSmsItems as $marketingSmsItem) {
            if ($marketingSmsItem->template->target_type->value === TargetTypeEnum::CLUE->value) {
                $this->handleClues($marketingSmsItem);
            } else if ($marketingSmsItem->template->target_type->value === TargetTypeEnum::STUDENT->value) {
                $this->handleStudents($marketingSmsItem);
            }
        }
    }

    private function handleClues(MarketingSmsItem $marketingSmsItem)
    {
        $clues = Clue::query()
            ->whereHas('professions', function (Builder $professionsQuery) use ($marketingSmsItem) {
                $professionsQuery->whereNull('course_register_id')
                    ->where('clue_profession.created_at', '<=', Carbon::now()->subSeconds($marketingSmsItem->after_time))
                    ->whereIn('profession_id', $marketingSmsItem->template->professions()->pluck('id')->toArray());
            })
            ->whereHas('user', function (Builder $userQuery) use ($marketingSmsItem) {
                $userQuery->whereDoesntHave('marketingSmsLogs', function (Builder $marketingSmsLogsQuery) use ($marketingSmsItem) {
                    $marketingSmsLogsQuery->where('marketing_sms_item_id', $marketingSmsItem->id);
                });
            })
            ->with(['user'])
            ->get();

        foreach ($clues as $clue) {
            dispatch(new SendMarketingSmsJob($clue->user, $marketingSmsItem));
        }
    }

    private function handleStudents(MarketingSmsItem $marketingSmsItem)
    {
        $students = Student::query()
            ->whereHas('courseRegisters', function (Builder $courseRegistersQuery) use ($marketingSmsItem) {
                $courseRegistersQuery
                    ->where('course_registers.created_at', '<=', Carbon::now()->subSeconds($marketingSmsItem->after_time))
                    ->whereHas('course', function (Builder $courseQuery) use ($marketingSmsItem) {
                        $courseQuery->whereIn(
                            'profession_id',
                            $marketingSmsItem->template->professions()->pluck('id')->toArray()
                        );
                    });
            })
            ->whereHas('user', function (Builder $userQuery) use ($marketingSmsItem) {
                $userQuery->whereDoesntHave('marketingSmsLogs', function (Builder $marketingSmsLogsQuery) use ($marketingSmsItem) {
                    $marketingSmsLogsQuery->where('marketing_sms_item_id', $marketingSmsItem->id);
                });
            })
            ->with(['user'])
            ->get();

        foreach ($students as $student) {
            dispatch(new SendMarketingSmsJob($student->user, $marketingSmsItem));
        }
    }
}
