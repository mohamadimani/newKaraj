<?php

namespace App\Livewire\Admin\Reports\SecretarySales;

use App\Models\Clue;
use App\Models\CourseRegister;
use App\Models\CourseReserve;
use App\Models\Profession;
use App\Models\Secretary;
use App\Models\Student;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    public $startDate;
    public $endDate;
    public $secretaryId;

    public $secretaryClues = 0;
    public $secretaryCluesToStudent = 0;
    public $secretaryStudents = 0;
    public $secretaryCancels = 0;
    public $secretaryReserved = 0;
    public $secretaryStudentInProfession = 0;

    public $cluesCount = 0;
    public $studentCount = 0;
    public $cancelCount = 0;
    public $reservedCount = 0;

    public $professionStudents = [];
    public $professionList = [];

    public $chartDays = [1];
    public $chartClues =  [1];
    public $chartStudents =  [1];

    protected $paginationTheme = 'bootstrap';
    use WithPagination, LivewireAlert;

    public function render()
    {
        $secretaries = Secretary::active()->get();
        return view('livewire.admin.reports.secretary-sales.index', compact('secretaries'));
    }

    public function getSecretarySaleInfo()
    {
        if (!$this->secretaryId) {
            return $this->alert('error', 'مشاور را انتخاب کنبد');
        }
        if (!$this->startDate or !$this->endDate) {
            return $this->alert('error', 'تاریخ  را انتخاب کنبد');
        }

        // calculate secretary all clues and students
        $this->professionList = array_flip(Profession::pluck('id', 'title')->toArray());
        $query = CourseRegister::query()->with('course');
        $clues = Clue::with('user');
        $courseRegisters = clone $query;
        $cancels = clone $query;
        $reserves = CourseReserve::query()->where('status', 'pending');

        if ($this->startDate) {
            $clues->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
            $courseRegisters->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
            $cancels->where('updated_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
            $reserves->where('updated_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $clues->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
            $courseRegisters->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
            $cancels->where('updated_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
            $reserves->where('updated_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        $courseRegistersModel = clone $courseRegisters;
        $cancelModel = clone $cancels;

        $this->cluesCount = $clues->count();
        $this->studentCount = $courseRegisters->whereIn('status', ['registered', 'technical'])->count();
        $this->cancelCount = $cancelModel->where('status', 'cancelled')->count();
        $this->reservedCount = $reserves->count();

        $this->secretaryClues = $clues->where('secretary_id', $this->secretaryId)->count();
        $this->secretaryCluesToStudent = $clues->where('student_id', '>', 0)->count();
        $this->secretaryStudents = $courseRegistersModel->where('secretary_id', $this->secretaryId)->whereIn('status', ['registered', 'technical'])->count();
        $this->secretaryCancels = $cancels->where('secretary_id', $this->secretaryId)->where('status', 'cancelled')->count();
        $this->secretaryReserved = $reserves->where('secretary_id', $this->secretaryId)->count();

        $registrations = collect($courseRegisters->get());
        $this->professionStudents = $registrations->groupBy(function ($item) {
            return $item->course->profession->id;
        });

        $secretaryStudentInProfessionRegisteration = collect($courseRegistersModel->get());
        $this->secretaryStudentInProfession = $secretaryStudentInProfessionRegisteration->groupBy(function ($item) {
            return $item->course->profession->id;
        });
    }

    public function setSecretaryId($secretaryId)
    {
        $this->secretaryId = $secretaryId;
    }
}
