<?php

namespace App\Livewire\Admin\CourseCancels;

use App\Models\Course;
use App\Models\PaymentMethod;
use App\Repositories\Course\CourseRegisterRepository;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $selectedSecretaryId = null;
    public $startDate = null;
    public $endDate = null;
    public null|int $courseCancelId = null;
    protected $listeners = [''];
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $courseRegisters = resolve(CourseRegisterRepository::class)->getListQuery(Auth::user())->where('status', 'cancelled');

        $courses = Course::active()->get();
        $paymentMethods = PaymentMethod::active()->get();

        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $courseRegisters->where(function ($query) use ($search) {
                $query->whereHas('course', function ($q) use ($search) {
                    $q->whereLike('title', "%$search%");
                });
                $query = $query->orWhereHas('student', function ($studentQ) use ($search) {
                    $studentQ->whereHas('user', function ($userQ) use ($search) {
                        userSearchQuery($userQ, $search);
                    });
                });
            });
        }

        if ($this->selectedSecretaryId) {
            $courseRegisters->where('secretary_id', $this->selectedSecretaryId);
        }
        if ($this->startDate) {
            $courseRegisters->where('updated_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $courseRegisters->where('updated_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        $courseRegisters = $courseRegisters->with(['student.user', 'course'])->orderBy('created_at', 'desc')->paginate(30);
        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user())->orderBy('is_active', 'desc')->get();
        return view('livewire.admin.course-cancels.index', compact('courseRegisters', 'courses', 'paymentMethods', 'secretaries'));
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }
}
