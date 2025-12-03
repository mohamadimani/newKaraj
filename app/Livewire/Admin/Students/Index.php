<?php

namespace App\Livewire\Admin\Students;

use App\Enums\CourseRegister\StatusEnum;
use App\Models\CourseRegister;
use App\Models\PaymentMethod;
use App\Models\Secretary;
use App\Models\User;
use App\Repositories\User\SecretaryRepository;
use App\Repositories\User\StudentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';
    public $selectedSecretaryId = null;
    public $startDate = null;
    public $endDate = null;
    public $courseRegisters = [];
    public $courseRegister = null;
    public $remainingAmount = null;
    public $userModel = null;

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $students = resolve(StudentRepository::class)->getListQuery(Auth::user())
            ->with(['user', 'user.clue', 'courseRegisters'])
            ->whereHas('courseRegisters', function ($query) {
                $query->whereIn('status', [StatusEnum::REGISTERED, StatusEnum::TECHNICAL]);
            });

        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $students = $students->whereHas('user', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->orWhere(DB::raw('CONCAT(first_name," ",last_name)'), 'LIKE', "%$search%")
                    ->orWhere('mobile', 'LIKE', "%$search%")
                    ->orWhereHas('province', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%$search%");
                    });
            });

            $students = $students->orWhereHas('courseRegisters', function ($query) use ($search) {
                $query->whereHas('course', function ($query) use ($search) {
                    $query->where('title', 'LIKE', "%$search%");
                });
            });
            $students = $students->orWhere('national_code', 'LIKE', "%$search%");
        }

        if (request()->query('course_id')) {
            $students->whereHas('courseRegisters', function ($query) {
                $query->where('course_id', request()->query('course_id'));
            });
        }
        if ($this->selectedSecretaryId) {
            $secretary = Secretary::find($this->selectedSecretaryId);
            $students->whereHas('courseRegisters', function ($query) use ($secretary) {
                $query->where('secretary_id', $secretary->id);
            });
        }
        if ($this->startDate) {
            $students->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $students->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }
        $students = $students->orderBy('created_at', 'desc')->paginate(30);
        $paymentMethods = PaymentMethod::all();
        $secretaries = Secretary::get();
        return view('livewire.admin.students.index', compact('students', 'paymentMethods', 'secretaries'));
    }

    public function setUser($userId)
    {
        $this->userModel = User::find($userId);
    }

    public function setCourseRegisters($studentId)
    {
        $this->courseRegisters = CourseRegister::where('student_id', $studentId)->get();
    }

    public function setRemainingAmount($courseRegisterId)
    {
        $courseRegister = CourseRegister::find($courseRegisterId);
        $this->remainingAmount = $courseRegister->amount > 0 ? ($courseRegister->amount - $courseRegister->paid_amount) : ($courseRegister->course->price - $courseRegister->paid_amount);
    }

    public function updatedCourseRegister()
    {
        $courseRegister = CourseRegister::find($this->courseRegister);
        if ($courseRegister) {
            $amount = $courseRegister->amount > 0 ? ($courseRegister->amount - $courseRegister->paid_amount) : ($courseRegister->course->price - $courseRegister->paid_amount);
            $this->remainingAmount = number_format($amount);
        }
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }
}
