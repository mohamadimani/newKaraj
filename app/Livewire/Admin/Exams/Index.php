<?php

namespace App\Livewire\Admin\Exams;

use App\Models\Exam;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    public $search = '';

    use WithPagination, LivewireAlert;
    public function render()
    {
        $search = trim($this->search);
        $exams = Exam::query();
        if (mb_strlen($search) > 1) {
            $exams->where('title', 'like', '%' . $search . '%');
        }
        $exams = $exams->orderBy('id', 'DESC')->paginate(30);
        return view('livewire.admin.exams.index', compact('exams'));
    }

    public function updateStatus(Exam $exam, $status)
    {
        $exam->is_active = $status;
        if ($exam->save()) {
            $this->alert('success', $status == 1 ? __('public.messages.successfully_activated') : __('public.messages.successfully_inactivated'));
        } else {
            $this->alert('error', __('public.messages.error_in_saving'));
        }
    }
}
