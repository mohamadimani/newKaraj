<?php

namespace App\Livewire\Admin\FollowUps;

use App\Models\FollowUp;
use App\Models\Secretary;
use App\Repositories\FollowUp\FollowUpRepository;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use LivewireAlert, WithPagination;
    public int $followUpId;
    public string $search = '';
    public string $isDone = '';
    public string $rememberTime = '';
    public string $created_at = '';
    public string $selectedSecretaryId = '';
    public null|string $queryUserId = null;
    public null|string $backUrl = null;
    protected $listeners = ['setDone'];

    public $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->queryUserId = request()->query('user_id');
        $this->backUrl = request()->query('back_url');
    }

    public function render()
    {
        $search = trim($this->search);
        $followUps = FollowUp::query();

        if (mb_strlen($search) > 2) {
            $followUps->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhereHas('user', function ($query) use ($search) {
                    userSearchQuery($query, $search);
                });
        }

        if ($this->isDone === '0' || $this->isDone === '1') {
            $followUps->where('is_done', $this->isDone);
        }

        if ($this->created_at) {
            $followUps->whereBetween('created_at', [
                Carbon::parse(toGeorgianDate($this->created_at))->startOfDay(),
                Carbon::parse(toGeorgianDate($this->created_at))->endOfDay()
            ]);
        }

        if ($this->selectedSecretaryId) {
            $user = Secretary::find($this->selectedSecretaryId)->user;
            $followUps->where('created_by', $user->id);
        }

        if ($this->queryUserId) {
            $followUps->where('user_id', $this->queryUserId);
        }

        $followUps = $followUps->with(['user', 'createdBy'])->orderBy('created_at', 'desc')->paginate(30);

        $secretaries = Secretary::query()->orderBy('created_at', 'desc')->get();
        return view('livewire.admin.follow-ups.index', compact('followUps', 'secretaries'));
    }

    public function markAsDone(int $followUpId)
    {
        $this->confirm(__('follow_ups.confirm_is_done'), [
            'onConfirmed' => 'setDone',
        ]);
        $this->followUpId = $followUpId;
    }

    public function setDone()
    {
        $followUp = FollowUp::find($this->followUpId);
        $followUp->update(['is_done' => true]);
        $this->alert('success', __('public.messages.successfully_done'));
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }
}
