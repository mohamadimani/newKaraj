<?php

namespace App\Livewire\Admin\Reports\Financial;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Livewire\Component;

class Index extends Component
{
    public $startDate;
    public $endDate;
    public $paymentStatus;

    public function render()
    {
        $payMethods = PaymentMethod::active()->get();
        $payments = Payment::query();

        if ($this->startDate) {
            $payments->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $payments->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        if ($this->paymentStatus != '') {
            $payments->where('status', $this->paymentStatus);
        }

        return view('livewire.admin.reports.financial.index', compact('payMethods', 'payments'));
    }
}
