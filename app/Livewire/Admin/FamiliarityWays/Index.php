<?php

namespace App\Livewire\Admin\FamiliarityWays;

use App\Models\FamiliarityWay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    public $title, $edit_title, $editRowId, $slug, $edit_slug, $sort, $edit_sort;
    public $familiarityWay;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['delete'];

    public function render()
    {
        $familiarityWays = FamiliarityWay::query()->paginate(30);

        return view('livewire.admin.familiarity-ways.index', compact('familiarityWays'));
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255|unique:familiarity_ways,title,NULL,id,deleted_at,NULL',
            'slug' => 'required|string|max:255|unique:familiarity_ways,slug,NULL,id,deleted_at,NULL',
            'sort' => 'required|integer',
        ]);

        $familiarityWay = FamiliarityWay::create([
            'title' => $this->title,
            'slug' => Str::slug($this->slug),
            'sort' => $this->sort,
            'created_by' => Auth::id(),
        ]);

        if ($familiarityWay) {
            $this->alert('success', __('public.messages.successfully_saved'));
            $this->title = null;
            $this->slug = null;
            $this->sort = null;
        } else {
            $this->alert('error', __('public.messages.error_in_saving'));
        }
    }

    public function updateFamiliarityWay()
    {
        $this->validate([
            'edit_title' =>  'required|string|max:255|unique:familiarity_ways,title,' . $this->editRowId . ',id,deleted_at,NULL',
            'edit_slug' => 'required|string|max:255|unique:familiarity_ways,slug,' . $this->editRowId . ',id,deleted_at,NULL',
            'edit_sort' => 'required|integer',
        ]);

        $familiarityWay = FamiliarityWay::where('id', $this->editRowId)->update([
            'title' => $this->edit_title,
            'slug' => $this->edit_slug,
            'sort' => $this->edit_sort,
        ]);
        if ($familiarityWay) {
            $this->edit_title = null;
            $this->edit_slug = null;
            $this->edit_sort = null;
            $this->editRowId = null;
            $this->alert('success', __('public.messages.successfully_updated'));
        } else {
            $this->alert('error', __('public.messages.error_in_updating'));
        }
    }

    public function setEditRowId(FamiliarityWay $familiarityWay)
    {
        $this->edit_title = $familiarityWay->title;
        $this->editRowId = $familiarityWay->id;
        $this->edit_slug = $familiarityWay->slug;
        $this->edit_sort = $familiarityWay->sort;
    }

    public function updateStatus($id, $status)
    {
        FamiliarityWay::where('id', $id)->update(['is_active' => $status]);
        if ($status == 1) {
            $this->alert('success', 'با موفقیت فعال شد');
        } else {
            $this->alert('success', 'با موفقیت غیر فعال شد');
        }
    }
}
