<?php

namespace App\Livewire\Admin\Branches;

use App\Models\Branch;
use App\Repositories\Branch\BranchRepository;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;
    public $branch;
    public $addBranch = false;

    use WithPagination, WithFileUploads, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['delete'];

    public function render()
    {
        $search = trim($this->search);
        $branchRepository = resolve(BranchRepository::class);
        $branches = $branchRepository->getListQuery();
        if (mb_strlen($search) > 2) {
            $branches = $branches->where('name', 'LIKE', "%$search%");
            $branches = $branches->orWhere('address', 'LIKE', "%$search%");
            $branches = $branches->orWhere('manager', 'LIKE', "%$search%");
            $branches = $branches->orWhere('bank_card_number', 'LIKE', "%$search%");
        }
        $branches = $branches->orderBy('id', 'DESC')->with('province')->paginate(30);

        return view('livewire.admin.branches.index', compact('branches'));
    }

    public function changeStatus(Branch $branch, $status)
    {
        $branch->is_active = $status;
        if ($branch->save()) {
            $this->alert('success', 'انجام شد');
        } else {
            $this->alert('error', 'انجام نشد');
        }
    }

    public function deleteBranch(Branch $branch)
    {
       return $this->alert('error', 'شعبه را نمیتوان حذف کرد');

        $this->confirm('برای حذف مطمئن هستید؟', [
            'onConfirmed' => 'delete',
        ]);

        $this->branch = $branch;
    }

    public function delete()
    {
        $this->branch->deleted_by = auth()->user()->id;
        $this->branch->save();
        if ($this->branch->delete()) {
            $this->alert('success', 'با موفقیت حذف شد');
        } else {
            $this->alert('error', 'مشکل در حذف');
        }
    }
}
