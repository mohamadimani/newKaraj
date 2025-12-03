<?php

namespace App\Livewire\Admin\Panel\Admin;

use App\Repositories\User\SalesTeamRepository;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $secretariesDailySell = \App\Models\SalesTeamSecretary::get();

        $salesTeams = resolve(SalesTeamRepository::class)->getListQuery()->where('id', '!=', 5)
        ->orderBy('created_at', 'desc')
        ->get();

        return view('livewire.admin.panel.admin.index', compact('secretariesDailySell','salesTeams'));
    }
}
