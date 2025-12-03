<?php

namespace App\Livewire\Admin\Phones;

use App\Models\Branch;
use App\Models\Phone;
use App\Models\PhoneInternal;
use App\Repositories\Branch\PhoneRepository;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class index extends Component
{
    public $number;
    public $branch_id;

    // for edit
    public $number_edit;
    public $phoneIdEdit;
    public $phoneModel;

    // for internal phone
    public $showInternalNumbers = false;
    public $title;
    public $title_edit;

    // for delete internal phone
    public $phoneModelInternal;
    public $internalNumberIdEdit;

    use WithPagination, WithFileUploads, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['delete', 'softDeleteInternal'];

    public function render()
    {
        if ($this->showInternalNumbers) {
            $internalNumbers = PhoneInternal::query()
                ->where('phone_id', $this->phoneModel->id)
                ->with(['phone.branch', 'secretary'])
                ->get();

            return view('livewire.admin.phones.internal', compact('internalNumbers'));
        } else {
            $phoneRepository = resolve(PhoneRepository::class);
            $phones = $phoneRepository->getListQuery()->with('branch')->paginate(30);
            $branches = Branch::where('is_active', true)->get();

            return view('livewire.admin.phones.index', compact('phones', 'branches'));
        }
    }

    public function phoneStore()
    {
        $this->validate([
            'number' => 'required|numeric|unique:phones,number,NULL,id,deleted_at,NULL',
            'branch_id' => 'required|numeric',
            'title' => 'nullable|string',
        ]);

        $phone = Phone::create([
            'number' => $this->number,
            'branch_id' => $this->branch_id,
            'created_by' => Auth::id(),
        ]);

        if ($phone) {
            $this->alert('success', __('public.messages.successfully_saved'));
            $this->number = null;
            $this->branch_id = null;
        } else {
            $this->alert('error', __('public.messages.error_in_saving'));
        }
    }

    public function editPhone(Phone $phone)
    {
        $this->number_edit = $phone->number;
        $this->phoneIdEdit = $phone->id;
    }

    public function updatePhone()
    {
        $this->validate([
            'number_edit' =>  'required|numeric|unique:phones,number,' . $this->phoneIdEdit . ',id,deleted_at,NULL',
        ]);

        $phone = Phone::where('id', $this->phoneIdEdit)->update(['number' => $this->number_edit]);
        if ($phone) {
            $this->number_edit = null;
            $this->phoneIdEdit = null;
            $this->alert('success', __('public.messages.successfully_updated'));
        } else {
            $this->alert('error', __('public.messages.error_in_updating'));
        }
    }

    public function updateStatus(Phone $phone, $status = false)
    {
        $phone->is_active = $status;
        $phone->save();
    }

    public function deletePhone(Phone $phone)
    {
        $this->confirm(__('public.messages.confirm_delete'), [
            'onConfirmed' => 'delete',
        ]);
        $this->phoneModel = $phone;
    }

    public function delete()
    {
        Phone::where('parent_id', $this->phoneModel->id)->delete();
        $this->phoneModel->deleted_by = Auth::id();
        $this->phoneModel->save();
        if ($this->phoneModel->delete()) {
            $this->alert('success', __('public.messages.successfully_deleted'));
        } else {
            $this->alert('error', __('public.messages.error_in_deleting'));
        }
    }

    // internal number action
    public function setInternalParentId(Phone $phone)
    {
        $this->showInternalNumbers = true;
        $this->phoneModel = $phone;
    }

    public function unSetInternalParentId()
    {
        $this->showInternalNumbers = false;
        $this->phoneModel = null;
    }

    public function internalStore()
    {
        $this->validate([
            'title' => 'required|string',
            'number' => 'required|numeric|unique:phone_internals,number,NULL,id,deleted_at,NULL,phone_id,' . $this->phoneModel->id,
        ]);

        $phoneInternal = PhoneInternal::create([
            'number' => $this->number,
            'phone_id' => $this->phoneModel->id,
            'title' => $this->title,
            'created_by' => Auth::id(),
        ]);
        if ($phoneInternal) {
            $this->alert('success', __('public.messages.successfully_saved'));
            $this->title = null;
            $this->number = null;
        } else {
            $this->alert('error', __('public.messages.error_in_saving'));
        }
    }

    public function editInternal(PhoneInternal $phoneInternal)
    {
        $this->title_edit = $phoneInternal->title;
        $this->number_edit = $phoneInternal->number;
        $this->internalNumberIdEdit = $phoneInternal->id;
    }

    public function updateInternal()
    {
        $this->validate([
            'title_edit' => 'required|string',
            'number_edit' => 'required|numeric|unique:phone_internals,number,' . $this->internalNumberIdEdit . ',id,deleted_at,NULL,phone_id,' . $this->phoneModel->id,
        ]);

        $phoneInternal = PhoneInternal::where('id', $this->internalNumberIdEdit)->update(['title' => $this->title_edit, 'number' => $this->number_edit]);
        if ($phoneInternal) {
            $this->alert('success', __('public.messages.successfully_saved'));
            $this->title_edit = null;
            $this->number_edit = null;
            $this->internalNumberIdEdit = null;
        } else {
            $this->alert('error', __('public.messages.error_in_saving'));
        }
    }


    public function deleteInternal(PhoneInternal $phoneInternal)
    {
        $this->confirm(__('public.confirm_delete'), [
            'onConfirmed' => 'softDeleteInternal',
        ]);
        $this->phoneModelInternal = $phoneInternal;
    }

    public function softDeleteInternal()
    {
        $this->phoneModelInternal->deleted_by = Auth::id();
        $this->phoneModelInternal->save();
        if ($this->phoneModelInternal->delete()) {
            $this->alert('success', __('public.messages.successfully_deleted'));
        } else {
            $this->alert('error', __('public.messages.error_in_deleting'));
        }
    }
}
