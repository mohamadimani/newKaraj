<?php

namespace App\Livewire\Admin\CoursePayments;

use App\Models\CoursePayment;
use App\Models\Secretary;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';
    public $selectedSecretaryId = null;
    public $startDate = null;
    public $endDate = null;
    public $onlinePaymentStatus = null;

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $coursePayments = CoursePayment::query()
            ->with('order')
            ->with('user');

        if ($this->selectedSecretaryId) {
            $secretary = Secretary::find($this->selectedSecretaryId);
            $coursePayments = $coursePayments->where('created_by', $secretary->user_id);
        }
        if ($this->startDate) {
            $coursePayments = $coursePayments->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $coursePayments = $coursePayments->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }
        if ($this->onlinePaymentStatus) {
            $coursePayments = $coursePayments->where('status', $this->onlinePaymentStatus);
        }
        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $coursePayments = $coursePayments->whereHas('user', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });
        }
      
        $coursePayments = $coursePayments->orderBy('created_at', 'desc')->paginate(30);
        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user())->orderBy('is_active', 'desc')->get();

        return view('livewire.admin.course-payments.index', compact('secretaries', 'coursePayments'));
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }
}
