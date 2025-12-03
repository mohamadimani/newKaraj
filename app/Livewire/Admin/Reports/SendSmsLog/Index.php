<?php

namespace App\Livewire\Admin\Reports\SendSmsLog;

use App\Models\SendSmsLog;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    use WithPagination, LivewireAlert;
    public function render()
    {
        $sendSmsLog = SendSmsLog::with('user');
        if ($this->search) {
            $search = trim($this->search);
            $sendSmsLog->whereHas('user', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });
            $sendSmsLog->orWhere('message', 'LIKE', "%$this->search%");
        }
        $sendSmsLog = $sendSmsLog->latest()->paginate(30);
        return view('livewire.admin.reports.send-sms-log.index', compact('sendSmsLog'));
    }
}
