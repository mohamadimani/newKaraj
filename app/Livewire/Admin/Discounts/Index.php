<?php

namespace App\Livewire\Admin\Discounts;

use App\Models\Discount;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $amount_type = '';
    public $discount_type = '';

    public function render()
    {
        $discounts = Discount::query();
        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $discounts->where('title', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%');
        }
        if ($this->amount_type) {
            $discounts->where('amount_type', $this->amount_type);
        }
        if ($this->discount_type) {
            $discounts->where('discount_type', $this->discount_type);
        }
        $discounts = $discounts->orderBy('id', 'desc')->paginate(30);

        return view('livewire.admin.discounts.index', compact('discounts'));
    }

    public function changeStatus($id, $status)
    {
        $discount = Discount::find($id);
        $discount->is_active = $status;
        if ($discount->save()) {
            $this->alert('success', __('discounts.messages.status_changed_successfully'));
        } else {
            $this->alert('error', __('discounts.messages.status_changed_failed'));
        }
    }
}
