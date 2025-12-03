<?php

namespace App\Livewire\Admin\GroupDescriptions;

use App\Models\GroupDescription;
use App\Models\Profession;
use App\Repositories\Profession\GroupDescriptionRepository;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use LivewireAlert, WithPagination;
    public string $search = '';
    public int $groupDescriptionDeleteId;
    public null|GroupDescription $groupProfessionDescription = null;
    public null|Collection $groupProfessionDescriptionCourses = null;
    protected $listeners = ['delete'];
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $professions = resolve(ProfessionRepository::class)->getListQuery(Auth::user())->active()->get();

        $groupDescriptions = resolve(GroupDescriptionRepository::class)->getListQuery();
        if (mb_strlen($this->search) > 1) {
            $search = trim($this->search);
            $groupDescriptions->where('description', 'like', '%' . $search . '%')
                ->orWhereHas('professions', function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%');
                    if (optional(Auth::user())->isSecretary()) {
                        $query->whereHas('branches', function (Builder $branchQuery) {
                            $branchQuery->whereIn('branches.id', Auth::user()->secretary->branchIds());
                        });
                    }
                    if (optional(Auth::user())->isClerk()) {
                        $query->whereHas('branches', function (Builder $branchQuery) {
                            $branchQuery->where('branch_id', Auth::user()->clerk->branch_id);
                        });
                    }
                });
        }
        $groupDescriptions = $groupDescriptions->paginate(30);

        return view('livewire.admin.group-descriptions.index', compact('groupDescriptions', 'professions'));
    }

    public function deleteConfirm(int $id)
    {
        $this->confirm('آیا مطمئن هستید؟', [
            'onConfirmed' => 'delete',
        ]);
        $this->groupDescriptionDeleteId = $id;
    }

    public function delete()
    {
        $groupDescription = GroupDescription::find($this->groupDescriptionDeleteId);
        $groupDescription->delete();

        return $this->alert('success', __('group_descriptions.messages.successfully_deleted'));
    }

    public function showProfessionDescription(GroupDescription $groupDescription)
    {
        $this->groupProfessionDescription = $groupDescription;
        $this->groupProfessionDescriptionCourses = $groupDescription->professionCourses();
    }
}
