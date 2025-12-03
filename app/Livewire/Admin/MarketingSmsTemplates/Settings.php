<?php

namespace App\Livewire\Admin\MarketingSmsTemplates;

use App\Models\MarketingSms\MarketingSmsTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class Settings extends Component
{
    public $marketingSmsTemplate;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function mount(MarketingSmsTemplate $marketingSmsTemplate)
    {
        $this->marketingSmsTemplate = $marketingSmsTemplate;
    }

    public function render()
    {
        $templateItems = $this->marketingSmsTemplate->items()->paginate(30);

        return view('livewire.admin.marketing-sms-templates.settings', compact('templateItems'));
    }
}
