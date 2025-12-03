<?php

namespace App\Livewire\Admin\Professions;

use App\Models\Branch;
use App\Models\Profession;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    public $title, $edit_title, $editRowId;
    public $price, $edit_price;
    public $duration_hours, $edit_duration_hours;
    public $capacity, $edit_capacity;

    public $profession;
    public $search;
    public $branches;

    // for branches view
    public $showBranches = false;
    public $branch_id;
    public $branch_ids = [];

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['delete'];

    public function render()
    {
        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository;

        $professions = $professions->getListQuery(Auth::user());

        if (mb_strlen($this->search) > 1) {
            $search = trim($this->search);
            $professions = $professions->where('title', 'like', "%$search%");
        }
        $professions = $professions->with('branches')
            ->paginate(30);
        $this->branches = Branch::active()->get();

        return view('livewire.admin.professions.index', compact('professions'));
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255|unique:professions,title,NULL,id,deleted_at,NULL',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|numeric|min:0',
            'capacity' => 'required|numeric|min:0',
            'branch_ids' => 'required|array',
        ]);

        $profession = Profession::create([
            'title' => $this->title,
            'price' => $this->price,
            'duration_hours' => $this->duration_hours,
            'capacity' => $this->capacity,
            'created_by' => Auth::id(),
        ]);
        $profession->branches()->sync($this->branch_ids);

        if ($profession) {
            $this->alert('success', __('public.messages.successfully_saved'));
            $this->title = null;
            $this->price = null;
            $this->duration_hours = null;
            $this->capacity = null;
            $this->branch_ids = [];
        } else {
            $this->alert('error', __('public.messages.error_in_saving'));
        }
    }

    public function updateProfession()
    {
        $this->validate([
            'edit_title' =>  'required|string|max:255|unique:professions,title,' . $this->editRowId . ',id,deleted_at,NULL',
            'edit_price' => 'required|numeric|min:0',
            'edit_duration_hours' => 'required|numeric|min:0',
            'edit_capacity' => 'required|numeric|min:0',
        ]);

        $profession = Profession::where('id', $this->editRowId)->update([
            'title' => $this->edit_title,
            'price' => $this->edit_price,
            'duration_hours' => $this->edit_duration_hours,
            'capacity' => $this->edit_capacity,
        ]);
        if ($profession) {
            $this->edit_title = null;
            $this->edit_price = null;
            $this->edit_duration_hours = null;
            $this->edit_capacity = null;
            $this->editRowId = null;
            $this->alert('success', __('public.messages.successfully_updated'));
        } else {
            $this->alert('error', __('public.messages.error_in_updating'));
        }
    }

    public function setEditRowId(Profession $profession)
    {
        $this->edit_title = $profession->title;
        $this->edit_price = $profession->price;
        $this->edit_duration_hours = $profession->duration_hours;
        $this->edit_capacity = $profession->capacity;
        $this->editRowId = $profession->id;
    }

    public function changeStatus(Profession $profession, $status)
    {
        $profession->is_active = $status;
        if ($profession->save()) {
            $this->alert('success', 'انجام شد');
        } else {
            $this->alert('error', 'انجام نشد');
        }
    }

    public function storeBranch(Profession $profession)
    {
        $this->validate([
            'branch_id' => 'required|exists:branches,id|unique:branch_profession,branch_id,' . $this->branch_id .    ',id,profession_id,' . $profession->id,
        ]);

        $profession->branches()->attach($this->branch_id);
        $this->alert('success', 'انجام شد');
        $this->branch_id = null;
    }

    public function deleteBranch(Profession $profession, $id)
    {
        $profession->branches()->detach($id);
        $this->alert('success', 'انجام شد');
    }

    public function setBranchesValue($value)
    {
        $this->branch_ids = $value;
    }
}
