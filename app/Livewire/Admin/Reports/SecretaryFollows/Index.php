<?php

namespace App\Livewire\Admin\Reports\SecretaryFollows;

use App\Models\Clue;
use App\Models\Secretary;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $startDate;
    public $endDate;
    public $secretaryId;
    public $secretary;

    public $clueFollowCount;
    public $secretaryFollow;

    public $clues;
    public $clueWithoutFollow;
    public $clueHasFollow;
    public $stepOneFollow;
    public $stepTwoFollow;
    public $stepThreeFollow;
    public $notAnswerFollow;
    public $closedFollow;

    // secretary reports
    public $secretaryClueHasFollow;
    public $secretaryStepOneFollow;
    public $secretaryStepTwoFollow;
    public $secretaryStepThreeFollow;
    public $secretaryNotAnswerFollow;
    public $secretaryClosedFollow;

    protected $paginationTheme = 'bootstrap';
    use WithPagination, LivewireAlert;

    public function render()
    {
        $secretaries = Secretary::active()->get();
        return view('livewire.admin.reports.secretary-follows.index', compact('secretaries'));
    }

    public function getSecretarySaleInfo()
    {
        if (!$this->secretaryId) {
            return $this->alert('error', 'مشاور را انتخاب کنبد');
        }
        if (!$this->startDate or !$this->endDate) {
            return $this->alert('error', 'تاریخ  را انتخاب کنبد');
        }

        $this->secretary = Secretary::find($this->secretaryId);

        $clues = Clue::with('user')->where('student_id', null)->select('id', 'secretary_id', 'user_id', 'created_at', 'student_id');

        if ($this->startDate and $this->endDate) {
            $startDate = date('Y-m-d H:i:s', jalaliToTimestamp($this->startDate));
            $endDate = date('Y-m-d H:i:s', jalaliToTimestamp($this->endDate) + 86400);
            $clues = $clues->whereBetween('created_at',  [$startDate, $endDate]);
        }
        $cluesQuery = clone $clues;
        $cluesHasFollow = clone $clues;
        $stepOneFollowQuery = clone $clues;
        $stepTwoFollowQuery = clone $clues;
        $stepThreeFollowQuery = clone $clues;
        $notAnswerFollowQuery = clone $clues;
        $closedFollowQuery = clone $clues;

        $this->clues = $cluesQuery->count();
        $this->clueWithoutFollow = $this->filterQuery($clues, 'clueWithoutFollow')->count();

        $this->clueHasFollow = $this->filterQuery($cluesHasFollow, 'clueHasFollow')->count();
        $this->stepOneFollow = $this->filterQuery($stepOneFollowQuery, 'stepOneFollow')->count();
        $this->stepTwoFollow = $this->filterQuery($stepTwoFollowQuery, 'stepTwoFollow')->count();
        $this->stepThreeFollow = $this->filterQuery($stepThreeFollowQuery, 'stepThreeFollow')->count();
        $this->notAnswerFollow = $this->filterQuery($notAnswerFollowQuery, 'notAnswerFollow')->count();
        $this->closedFollow = $this->filterQuery($closedFollowQuery, 'closedFollow')->count();

        // $this->secretaryClueHasFollow = $this->filterQuery($cluesHasFollow, 'clueHasFollow')->count();
        $this->secretaryClueHasFollow = $this->filterQuery($cluesHasFollow, 'clueHasFollow')->where('created_by', $this->secretary->user_id)->count();
        $this->secretaryStepOneFollow = $this->filterQuery($stepOneFollowQuery, 'stepOneFollow')->where('created_by', $this->secretary->user_id)->count();
        $this->secretaryStepTwoFollow = $this->filterQuery($stepTwoFollowQuery, 'stepTwoFollow')->where('created_by', $this->secretary->user_id)->count();
        $this->secretaryStepThreeFollow = $this->filterQuery($stepThreeFollowQuery, 'stepThreeFollow')->where('created_by', $this->secretary->user_id)->count();
        $this->secretaryNotAnswerFollow = $this->filterQuery($notAnswerFollowQuery, 'notAnswerFollow')->where('created_by', $this->secretary->user_id)->count();
        $this->secretaryClosedFollow = $this->filterQuery($closedFollowQuery, 'closedFollow')->where('created_by', $this->secretary->user_id)->count();

        // dd($this->clues, $this->clueWithoutFollow, $this->clueHasFollow);
    }

    public function setSecretaryId($secretaryId)
    {
        $this->secretaryId = $secretaryId;
    }

    public function filterQuery($clues, $filter)
    {
        if ($filter == 'clueWithoutFollow') {
            $clues = $clues->whereDoesntHave('user.followUps');
        }

        if ($filter == 'clueHasFollow') {
            $clues =  $clues->whereHas('user.followUps');
        }

        if ($filter == 'stepOneFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->whereNull('step')->orWhere('step', 'step1');
                });
            });
        }

        if ($filter == 'stepTwoFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->where('step', 'step2');
                });
            });
        }

        if ($filter == 'stepThreeFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->where('step', 'step3');
                });
            });
        }

        if ($filter == 'notAnswerFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->where('step', 'not_answer');
                });
            });
        }

        if ($filter == 'closedFollow') {
            $clues =  $clues->where(function ($query) {
                $query->whereHas('user.followUps', function ($query) {
                    $query->where('step', 'closed');
                });
            });
        }
        return $clues;
    }
}
