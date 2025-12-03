<?php

namespace App\Livewire\Admin\TechnicalAddress;

use App\Models\Branch;
use App\Models\Province;
use App\Models\TechnicalAddress;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $technicalAddress_edit = false;

    // create vars
    public $title;
    public $address;
    public $phone;
    public $branch_id;
    public $province_id;
    // edit vars
    public $title_edit;
    public $address_edit;
    public $phone_edit;
    public $branch_id_edit;
    public $province_id_edit;
    // models
    public $technicalAddress;
    public $branches;
    public $provinces;

    use LivewireAlert;
    protected $listeners = ['delete'];

    public function render()
    {
        $technicalAddresses = TechnicalAddress::query();
        if (mb_strlen($this->search) > 1) {
            $search = trim($this->search);
            $technicalAddresses = $technicalAddresses->where('title', 'like', '%' . $search . '%')->orWhere('address', 'like', '%' . $search . '%')->orWhere('phone', 'like', '%' . $search . '%');
        }
        $technicalAddresses = $technicalAddresses->get();
        return view('livewire.admin.technical-address.index', compact('technicalAddresses'));
    }

    public function mount()
    {
        $this->branches = Branch::active()->get();
        $this->provinces = Province::get();
    }

    public function deleteAddress(TechnicalAddress $technicalAddress)
    {
        $this->confirm(__('public.messages.confirm_delete'), [
            'onConfirmed' => 'delete',
        ]);
        $this->technicalAddress = $technicalAddress;
    }

    public function delete()
    {
        $this->technicalAddress->deleted_by = auth()->user()->id;
        $this->technicalAddress->save();
        if ($this->technicalAddress->delete()) {
            $this->alert('success', __('public.messages.successfully_deleted'));
        } else {
            $this->alert('error', __('public.messages.error_in_deleting'));
        }
    }

    public function update(TechnicalAddress $technicalAddress)
    {
        $this->validate([
            'title_edit' => 'required|string|max:255',
            'address_edit' => 'required|string|max:255',
            'phone_edit' => 'required|numeric',
            'branch_id_edit' => 'required|exists:branches,id',
            'province_id_edit' => 'required|exists:provinces,id',
        ]);


        $technicalAddress->title = $this->title_edit;
        $technicalAddress->address = $this->address_edit;
        $technicalAddress->phone = $this->phone_edit;
        $technicalAddress->branch_id = $this->branch_id_edit;
        $technicalAddress->province_id = $this->province_id_edit;
        if ($technicalAddress->save()) {
            $this->technicalAddress_edit = false;
            $this->alert('success', __('public.messages.successfully_updated'));
        } else {
            $this->alert('error', __('public.messages.error_in_updating'));
        }
    }

    public function edit(TechnicalAddress $technicalAddress)
    {
        $this->technicalAddress_edit = $technicalAddress->id;
        $this->title_edit = $technicalAddress->title;
        $this->address_edit = $technicalAddress->address;
        $this->phone_edit = $technicalAddress->phone;
        $this->branch_id_edit = $technicalAddress->branch_id;
        $this->province_id_edit = $technicalAddress->province_id;
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|numeric|min:8',
            'branch_id' => 'required|exists:branches,id',
            'province_id' => 'required|exists:provinces,id',
        ]);

        $technicalAddress = TechnicalAddress::create([
            'title' => $this->title,
            'address' => $this->address,
            'phone' => $this->phone,
            'branch_id' => $this->branch_id,
            'province_id' => $this->province_id,
        ]);

        if ($technicalAddress) {
            $this->reset('title', 'address', 'phone', 'branch_id', 'province_id');
            $this->alert('success', __('public.messages.successfully_saved'));
        } else {
            $this->alert('error', __('public.messages.error_in_saving'));
        }
    }
}
