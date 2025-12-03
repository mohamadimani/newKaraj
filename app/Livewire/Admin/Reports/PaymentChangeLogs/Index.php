<?php

namespace App\Livewire\Admin\Reports\PaymentChangeLogs;

use App\Models\PaymentChangeLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    public function render()
    {
        $paymentLogs = PaymentChangeLog::with('payment', 'createdBy')->orderBy('created_at', 'desc')->paginate(30);
        $paymentLogs = PaymentChangeLog::with(['payment' => function ($query) {
            $query->withTrashed();
        }, 'createdBy'])->orderBy('created_at', 'desc')->paginate(30);
        return view('livewire.admin.reports.payment-change-logs.index', compact('paymentLogs'));
    }
}
