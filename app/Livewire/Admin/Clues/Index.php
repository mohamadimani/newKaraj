<?php

namespace App\Livewire\Admin\Clues;

use App\Jobs\SendSingleSmsJob;
use App\Models\Clue;
use App\Models\Secretary;
use App\Repositories\Profession\ProfessionRepository;
use App\Repositories\User\ClueRepository;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';
    public $selectedSecretaryId = null;
    public $startDate = null;
    public $endDate = null;
    public $profession_id;
    public array $selectedClues = [];
    public $selectAllClues = '';
    public $smsMessage = '';

    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $clues = resolve(ClueRepository::class)->getListQuery(Auth::user());
        $clues->where(function ($query) {
            $query->whereNull('student_id')
                ->orWhereHas('professions', function ($query) {
                    $query->whereNull('course_register_id');
                });
        });
        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $clues = $clues->whereHas('user', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });
        }

        if ($this->selectedSecretaryId) {
            $clues->where('secretary_id', $this->selectedSecretaryId);
        }
        if ($this->startDate) {
            $clues->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $clues->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }
        if ($this->profession_id) {
            $clues->whereHas('professions', function ($query) {
                $query->where('profession_id', $this->profession_id)
                    ->where('course_register_id', null);
            });
        }

        if ($this->selectAllClues == 'yes') {
            $this->selectedClues = $clues->pluck('id')->toArray();
        }

        if ($this->selectAllClues == 'no') {
            $this->selectedClues = [];
        }

        $clues = $clues->with(['professions', 'user'])->orderBy('id', 'DESC')->paginate(30);
        $secretaries = Secretary::get();
        $professions = resolve(ProfessionRepository::class)->getListQuery(Auth::user())->get();
        return view('livewire.admin.clues.index', compact('clues', 'secretaries', 'professions'));
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }

    public function setSelectedProfessionId($pofessionId)
    {
        $this->profession_id = $pofessionId;
    }

    public function setSelectedClueId($clueId)
    {
        $this->selectAllClues = null;
        $this->selectedClues[] = $clueId;
    }

    public function unsetSelectedClueId($clueId)
    {
        $this->selectAllClues = null;
        $this->selectedClues = array_diff($this->selectedClues, [$clueId]);
    }

    public function sendSms()
    {
        if (mb_strlen($this->smsMessage) < 1) {
            return $this->alert('error', 'متن پیام نمیتواند خالی باشد');
        }
        if (count($this->selectedClues) == 0) {
            return $this->alert('error', 'حداقل یک شماره برای ارسال انتخاب کنید');
        }
        foreach ($this->selectedClues as $clueId) {
            $clue = Clue::find($clueId);
            dispatch(new SendSingleSmsJob(
                $clue->user,
                $this->smsMessage
            ));
        }
        $this->smsMessage = '';
        $this->selectedClues = [];
        $this->selectAllClues = null;
        return $this->alert('success', __('messages.group_sms_sent'));
    }
}
