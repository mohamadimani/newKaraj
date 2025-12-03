<?php

namespace App\Livewire\Admin\Reports\VerificationCodeLog;

use App\Models\VerificationCode;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;


class Index extends Component
{
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    public function render()
    {
        $verificationCodes = VerificationCode::with('user');
        if ($this->search) {
            $search = trim($this->search);
            $verificationCodes->whereHas('user', function ($query) use($search){
                userSearchQuery($query, $search);
            });
            $verificationCodes->orWhere('otp' , 'LIKE' , "%$this->search%");
        }
        $verificationCodes = $verificationCodes->latest()->paginate(30);
        return view('livewire.admin.reports.verification-code-log.index', [
            'verificationCodes' => $verificationCodes
        ]);
    }
}
