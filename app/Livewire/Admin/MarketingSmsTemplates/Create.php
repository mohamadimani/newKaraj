<?php

namespace App\Livewire\Admin\MarketingSmsTemplates;

use App\Models\Branch;
use App\Models\Profession;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $selectedBranchId = null;

    public function render()
    {
        $branches = Branch::query()->active()->limit(10)->get();
       
        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $professions = $professions->active()->get();

        // if (!is_null($this->selectedBranchId)) {
        //     $professions->whereHas('branches', function ($query) {
        //         $query->where('branches.id', $this->selectedBranchId);
        //     });
        // }

        return view('livewire.admin.marketing-sms-templates.create', compact('branches', 'professions'));
    }

    public function setSelectedBranchId($value)
    {
        $this->selectedBranchId = $value;
    }
}
