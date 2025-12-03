<?php

namespace App\Livewire\Admin\Panel\Secretary;

use App\Constants\PermissionTitle;
use App\Models\Clue;
use App\Models\FollowUp;
use App\Models\User;
use App\Repositories\User\ClueRepository;
use App\Repositories\User\SalesTeamRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;
    public $startDate;
    public $endDate;
    public $title;
    public $description;
    public $userId;
    public $userName;
    public $filter = 'todayFollow';
    public $filtersName = [
        'todayFollow' => 'سرنخ های بدون پیگیری',
        'doneFollow' => 'پیگیری های انجام شده',
        'twoStepFollow' => 'پیگیری های مرحله دوم',
        'threeStepFollow' => 'پیگیری های مرحله سوم',
        'notAnswerFollow' => 'پیگیری های عدم پاسخ',
        'closedFollow' => 'پیگیری های بسته شده',
    ];

    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public function render(Request $request)
    {
        $clues = Clue::with('user')->where('student_id', null)->select('id', 'secretary_id', 'user_id', 'created_at', 'student_id');

        if (!Auth::user()->hasPermissionTo(PermissionTitle::ADMIN_CLUE)) {
            $clues = $clues->where('secretary_id', User()?->secretary?->id);
        }

        $search = trim($this->search);
        if (mb_strlen($search) > 1) {
            $clues = $clues->whereHas('user', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });
        }

        if ($this->startDate and $this->endDate) {
            $startDate = date('Y-m-d H:i:s', jalaliToTimestamp($this->startDate));
            $endDate = date('Y-m-d H:i:s', jalaliToTimestamp($this->endDate) + 86400);
            $clues = $clues->whereBetween('created_at',  [$startDate, $endDate]);
        } else {
            $clues = $clues->whereBetween('created_at',  [now()->subDays(14),  now()]);
        }

        $clues = $this->filterQuery($clues);

        $data = $clues->orderBy('created_at', 'DESC')->get();

        $salesTeams = resolve(SalesTeamRepository::class)->getListQuery()->where('id', '!=', 5)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('livewire.admin.panel.secretary.index', compact('data', 'salesTeams'));
    }

    public function mount()
    {
        $this->startDate = verta()->subDays(14)->format('Y-m-d');
        $this->endDate = verta()->now()->format('Y-m-d');
    }

    public function setFollowUpFilter($filter)
    {
        $this->filter = $filter;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        $this->userName = User::find($userId)->fullName;
    }

    public function addFollowUp($step)
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'userId' => 'required',
            //'step' => 'in:step1,step2,step3,not_answer,register,close',
        ]);

        $followUp = FollowUp::create([
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->userId,
            'step' => $step,
            'not_answer_count' => 0,
            'created_by' => Auth::user()->id,
            'remember_time' => now()->timezone('Asia/Tehran'),
        ]);

        if ($followUp) {

            if ($step == 'not_answer') {
                FollowUp::where('user_id', $this->userId)->increment('not_answer_count');

                $hasFollowUps = FollowUp::where('user_id', $this->userId)->first();

                if ($hasFollowUps->not_answer_count >= 3) {
                    FollowUp::where('user_id', $this->userId)->update(['step' => 'not_answer', 'not_answer_count' => 3]);
                } else {
                    if ($this->filter == 'todayFollow' or $this->filter == 'doneFollow') {
                        $filterStep = 'step1';
                    }
                    if ($this->filter == 'twoStepFollow') {
                        $filterStep = 'step2';
                    }
                    if ($this->filter == 'threeStepFollow') {
                        $filterStep = 'step3';
                    }
                    if ($this->filter == 'closedFollow') {
                        $filterStep = 'closed';
                    }
                    if ($this->filter == 'notAnswerFollow') {
                        $filterStep = 'not_answer';
                    }
                    FollowUp::where('user_id', $this->userId)->update(['step' => $filterStep]);
                }
            } else {
                FollowUp::where('user_id', $this->userId)->update(['step' => $step, 'not_answer_count' => 0]);
            }

            $this->reset('title', 'description', 'userId');
            return $this->alert('success', __('public.messages.successfully_done'));
        }
        return $this->alert('error', __('public.messages.failed'));
    }

    public function filterQuery($clues)
    {
        if ($this->filter == 'todayFollow') {
            $clues = $clues->whereDoesntHave('user.followUps');
        }

        if ($this->filter == 'doneFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->whereNull('step')->orWhere('step', 'step1');
                });
            });
        }

        if ($this->filter == 'twoStepFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->where('step', 'step2');
                });
            });
        }

        if ($this->filter == 'threeStepFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->where('step', 'step3');
                });
            });
        }

        if ($this->filter == 'notAnswerFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->where('step', 'not_answer');
                });
            });
        }

        if ($this->filter == 'closedFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->where('step', 'closed');
                });
            });
        }
        return $clues;
    }
}
