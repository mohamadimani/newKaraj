<?php

namespace App\Livewire\Admin\CourseReserves;

use App\Enums\CourseReserve\StatusEnum;
use App\Models\CourseReserve;
use App\Models\Secretary;
use App\Repositories\Course\CourseReserveRepository;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, LivewireAlert;
    public null|int $courseReserveId = null;
    protected $listeners = ['cancel'];
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $selectedSecretaryId = null;
    public $startDate = null;
    public $endDate = null;

    public function render()
    {
        $courseReserves = resolve(CourseReserveRepository::class)->getListQuery();

        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $courseReserves->whereHas('clue', function ($q) use ($search) {
                $q->whereHas('user', function ($userQ) use ($search) {
                    userSearchQuery($userQ, $search);
                });
            });
        }

        if ($this->selectedSecretaryId) {
            $courseReserves->where('secretary_id', $this->selectedSecretaryId);
        }
        if ($this->startDate) {
            $courseReserves->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $courseReserves->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        $courseReserves = $courseReserves->where('status' , StatusEnum::PENDING)
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user())->active()->orderBy('is_active', 'desc')->get();
        return view('livewire.admin.course-reserves.index', compact('courseReserves', 'secretaries'));
    }

    public function cancelCourseReserve($courseReserveId)
    {
        $this->confirm(__('course_reserves.messages.confirm_cancel'), [
            'onConfirmed' => 'cancel',
        ]);
        $this->courseReserveId = $courseReserveId;
    }

    public function cancel()
    {
        $courseReserve = CourseReserve::find($this->courseReserveId);
        $courseReserve->update(['status' => StatusEnum::CANCELLED]);
        $this->alert('success', __('course_reserves.messages.successfully_cancelled'));
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }
}
