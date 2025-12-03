<?php

namespace App\Livewire\Admin\Refund;

use Livewire\Component;
use App\Models\Refund;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;


class Index extends Component
{
    use LivewireAlert, WithPagination;

    public string $search = '';
    public string $startDate = '';
    public string $endDate = '';
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $refunds = Refund::active()->with(['user', 'course', 'courseRegister']);

        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $refunds->where(function ($query) use ($search) {
                $query->orWhereHas('user', function ($query) use ($search) {
                    userSearchQuery($query, $search);
                })
                    ->orWhereHas('course', function ($query) use ($search) {
                        $query->where('title', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($this->startDate) {
            $refunds->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $refunds->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        $refunds = $refunds->orderBy('created_at', 'desc')->paginate(30);

        return view('livewire.admin.refund.index', compact('refunds'));
    }

    public function confirmRefund($refundId)
    {
        $refund = Refund::find($refundId);
        $refund->confirmed_by = user()->id;
        $refund->confirmed_at = time();
        if ($refund->save()) {
            $this->alert('success', __('public.messages.successfully_updated'));
        } else {
            $this->alert('error', __('public.messages.error_in_updating'));
        }
    }
}
