<?php

namespace App\Livewire\Admin\OnlineCoursePercentages;

use App\Models\Teacher;
use Hekmatinasser\Verta\Facades\Verta;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';
    public $from_date = '';
    public $from_date_timestamp = '';
    public $to_date = '';
    public $to_date_timestamp = '';
    public $teacher;

    protected $listeners = ['withdrawTeacherPercent' ];

    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $teachers = Teacher::query()->with('orderItems')->with('user')
            ->whereHas('orderItems', function ($query) {
                $query->whereBetween('pay_date', [$this->from_date_timestamp, $this->to_date_timestamp + 86400]);
            })
            ->whereHas('user', function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            })->active()->paginate(30);
        return view('livewire.admin.online-course-percentages.index', compact('teachers'));
    }

    public function mount()
    {
        $this->from_date = Verta(now()->subDays(30)->timestamp)->format('Y/m/d');
        $this->to_date = Verta(now()->timestamp)->format('Y/m/d');
        $this->from_date_timestamp = now()->subDays(30)->timestamp;
        $this->to_date_timestamp = now()->timestamp;
    }

    public function calcTecherPercentage(Teacher $teacher)
    {
        $total = 0;
        foreach ($this->getTechetOrderItems($teacher)  as $orderItem) {
            $total += $orderItem->teacher_percent;
        }
        return $total;
    }

    public function updatedFromDate()
    {
        $fromDateTimestamp = verta(toGeorgianDate($this->from_date))->timestamp;
        $toDateTimestamp = verta(toGeorgianDate($this->to_date))->timestamp;
        if ($fromDateTimestamp > $toDateTimestamp) {
            $this->alert('error', 'تاریخ شروع نمیتواند بزرگتر از تاریخ پایان باشد');
            return;
        }
        $this->from_date_timestamp = $fromDateTimestamp;
        $this->to_date_timestamp = $toDateTimestamp;
    }

    public function updatedToDate()
    {
        $fromDateTimestamp = verta(toGeorgianDate($this->from_date))->timestamp;
        $toDateTimestamp = verta(toGeorgianDate($this->to_date))->timestamp;
        if ($fromDateTimestamp > $toDateTimestamp) {
            $this->alert('error', 'تاریخ شروع نمیتواند بزرگتر از تاریخ پایان باشد');
            return;
        }
        $this->from_date_timestamp = $fromDateTimestamp;
        $this->to_date_timestamp = $toDateTimestamp;
    }

    public function withdrawTeacherPercent()
    {
        foreach ($this->getTechetOrderItems($this->teacher)  as $orderItem) {
            $orderItem->update(['teacher_withdraw_date' => time(), 'teacher_withdraw' => true]);
        }
        $this->alert('success', 'پورسانت با موفقیت تسویه شد');
    }

    public function withdrawTeacherPercentConfirm(Teacher $teacher)
    {
        $this->confirm('آیا مطمئن هستید؟', [
            'onConfirmed' => 'withdrawTeacherPercent',
        ]);
        $this->teacher = $teacher;
    }

    public function getTechetOrderItems(Teacher $teacher)
    {
        return $teacher->orderItems->whereBetween('pay_date', [$this->from_date_timestamp, $this->to_date_timestamp + 86400]);
    }
}
