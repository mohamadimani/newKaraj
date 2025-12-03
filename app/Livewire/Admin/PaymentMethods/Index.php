<?php

namespace App\Livewire\Admin\PaymentMethods;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    public $title, $edit_title, $editRowId, $slug, $edit_slug, $sort, $edit_sort, $description, $edit_description;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['delete'];

    public function render()
    {
        $paymentMethods = PaymentMethod::query()->paginate(30);

        return view('livewire.admin.payment-methods.index', compact('paymentMethods'));
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255|unique:payment_methods,title,NULL,id,deleted_at,NULL',
            'slug' => 'required|string|max:255|unique:payment_methods,slug,NULL,id,deleted_at,NULL',
            'sort' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $paymentMethod = PaymentMethod::create([
            'title' => $this->title,
            'slug' => Str::slug($this->slug),
            'sort' => $this->sort,
            'description' => $this->description,
            'created_by' => Auth::id(),
        ]);

        if ($paymentMethod) {
            $this->alert('success', __('public.messages.successfully_saved'));
            $this->title = null;
            $this->slug = null;
            $this->sort = null;
            $this->description = null;
        } else {
            $this->alert('error', __('public.messages.error_in_saving'));
        }
    }

    public function updatePaymentMethod()
    {
        $this->validate([
            'edit_title' =>  'required|string|max:255|unique:payment_methods,title,' . $this->editRowId . ',id,deleted_at,NULL',
            'edit_slug' => 'required|string|max:255|unique:payment_methods,slug,' . $this->editRowId . ',id,deleted_at,NULL',
            'edit_sort' => 'required|integer',
            'edit_description' => 'nullable|string',
        ]);

        $paymentMethod = PaymentMethod::where('id', $this->editRowId)->update([
            'title' => $this->edit_title,
            'slug' => $this->edit_slug,
            'sort' => $this->edit_sort,
            'description' => $this->edit_description,
        ]);
        if ($paymentMethod) {
            $this->edit_title = null;
            $this->edit_slug = null;
            $this->edit_sort = null;
            $this->edit_description = null;
            $this->editRowId = null;
            $this->alert('success', __('public.messages.successfully_updated'));
        } else {
            $this->alert('error', __('public.messages.error_in_updating'));
        }
    }

    public function setEditRowId(PaymentMethod $paymentMethod)
    {
        $this->edit_title = $paymentMethod->title;
        $this->editRowId = $paymentMethod->id;
        $this->edit_slug = $paymentMethod->slug;
        $this->edit_sort = $paymentMethod->sort;
        $this->edit_description = $paymentMethod->description;
    }

    public function updateStatus($id, $status)
    {
        PaymentMethod::where('id', $id)->update(['is_active' => $status]);
        if ($status == 1) {
            $this->alert('success', 'با موفقیت فعال شد');
        } else {
            $this->alert('success', 'با موفقیت غیر فعال شد');
        }
    }
}
