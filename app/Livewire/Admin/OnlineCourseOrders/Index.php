<?php

namespace App\Livewire\Admin\OnlineCourseOrders;

use App\Models\Order;
use App\Models\Secretary;
use App\Models\User;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';
    public $selectedSecretaryId = null;
    public $startDate = null;
    public $endDate = null;
    public $onlinePayStatus = null;

    use WithPagination, LivewireAlert, WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $orders = Order::query();
        if (mb_strlen($this->search) > 0) {
            $search = trim($this->search);
            $orders->where('id', 'like', '%' . $search . '%')
                ->orWhereHas('user', function ($query) use ($search) {
                    userSearchQuery($query, $search);
                });
        }
        if ($this->selectedSecretaryId) {
            $secretary = Secretary::find($this->selectedSecretaryId);
            $orders = $orders->where('created_by', $secretary->user->id);
        }
        if ($this->startDate) {
            $orders->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $orders->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        $orders = $orders->with('user')->orderBy('created_at', 'desc')->paginate(30);




        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user())->orderBy('is_active','desc')->get();

        return view('livewire.admin.online-course-orders.index', compact('orders', 'secretaries'));
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }

    public function makeStudentAccount($userId)
    {;
        if ($user = User::find($userId)) {
            addClueToStudent($user);
            return $this->alert('success', 'با موفقیت ایجاد شد');
        }
        return $this->alert('error', 'مشکلی در انجام عملیات بوجود آمده است');
    }
}
