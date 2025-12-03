<?php

namespace App\Livewire\Admin\Reports\OrderItemChangeLogs;

use App\Models\OrderItemChangeLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;

    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    public function render()
    {
        $orderItemChangeLogs = OrderItemChangeLog::with(['orderItem', 'createdBy']);

        $search = trim($this->search);
        if (mb_strlen($search) > 1) {
            $courseRegisterLogs = $orderItemChangeLogs->whereHas('createdBy', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });

            $courseRegisterLogs = $orderItemChangeLogs->orWhereHas('orderItem.onlineCourse', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $orderItemChangeLogs = $orderItemChangeLogs->latest()->paginate(30);
        return view('livewire.admin.reports.order-item-change-logs.index', compact('orderItemChangeLogs'));
    }
}
