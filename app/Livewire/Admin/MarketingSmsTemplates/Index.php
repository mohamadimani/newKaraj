<?php

namespace App\Livewire\Admin\MarketingSmsTemplates;

use App\Models\Branch;
use App\Models\MarketingSms\MarketingSmsTemplate;
use App\Repositories\MarketingSms\MarketingSmsTemplateRepository;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $filterBranchId = null;
    public $filterTargetType = null;

    public function render()
    {
        $marketingSmsTemplateRepository = resolve(MarketingSmsTemplateRepository::class);
        $templates = $marketingSmsTemplateRepository->getListQuery();

        if (mb_strlen($this->search) > 1) {
            $search = trim($this->search);
            $templates->where('title', 'like', '%' . $search . '%')
                ->orWhereHas('professions', function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%');
                });
        }

        if ($this->filterBranchId) {
            $templates->where('branch_id', $this->filterBranchId);
        }

        if ($this->filterTargetType) {
            $templates->where('target_type', $this->filterTargetType);
        }

        $templates = $templates->with(['professions', 'branch'])->orderBy('created_at', 'desc')->paginate(30);
        $branches = Branch::query()->active()->get();

        return view('livewire.admin.marketing-sms-templates.index', compact('templates', 'branches'));
    }
}
