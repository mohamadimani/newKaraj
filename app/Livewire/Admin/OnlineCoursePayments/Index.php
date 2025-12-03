<?php

namespace App\Livewire\Admin\OnlineCoursePayments;

use App\Models\Branch;
use App\Models\OnlinePayment;
use App\Models\Secretary;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $branche_id;
    public $search;
    public $selectedSecretaryId;
    public $startDate;
    public $endDate;
    public $onlinePaymentStatus;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $onlinePayments = OnlinePayment::query()
            ->with('order')
            ->with('user');

        if ($this->selectedSecretaryId) {
            $secretary = Secretary::find($this->selectedSecretaryId);
            $onlinePayments = $onlinePayments->where('created_by', $secretary->user_id);
        }
        if ($this->startDate) {
            $onlinePayments = $onlinePayments->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $onlinePayments = $onlinePayments->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        if ($this->onlinePaymentStatus) {
            $onlinePayments = $onlinePayments->where('status', $this->onlinePaymentStatus);
        }
        if ($this->branche_id) {
            $onlinePayments = $onlinePayments->whereHas('user', function ($query) {
                $query = $query->whereHas('clue', function ($query) {
                    $query->where('branch_id', $this->branche_id);
                });
            });
        }
        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $onlinePayments = $onlinePayments->whereHas('user', function ($query) use($search){
                userSearchQuery($query, $search);
            });
        }
        $onlinePayments = $onlinePayments->orderBy('created_at', 'desc')->paginate(30);

        $branches = Branch::all();
        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user())->orderBy('is_active','desc')->get();
        return view('livewire.admin.online-course-payments.index', compact('onlinePayments', 'secretaries', 'branches'));
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }
}
