<?php

namespace App\Livewire\Admin\Courses;

use App\Models\Teacher;
use Livewire\Component;
use App\Repositories\Course\CourseRepository;
use App\Repositories\User\TeacherRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class Index extends Component
{
    public $startDate = null;
    public $endDate = null;
    public $search = '';
    public $teacher_id = '';
    public $courseListShowType = '';


    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $courses = resolve(CourseRepository::class)->getListQuery(Auth::user());

        if (mb_strlen($this->search) > 1) {
            $search = trim($this->search);
            $courses = $courses->where('title', 'LIKE', "%$search%");
        }
        if (mb_strlen($this->teacher_id) > 0) {
            $courses = $courses->where('teacher_id', $this->teacher_id);
        }

        if ($this->courseListShowType == 'notStart') {
            $courses = $courses->where('end_date', '>=', now()->format('Y-m-d'));
        }

        if ($this->courseListShowType == 'end') {
            $courses = $courses->where('end_date', '<', now()->format('Y-m-d'));
        }

        if ($this->startDate) {
            $courses->where('start_date', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $courses->where('start_date', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate)));
        }

        $courses = $courses->with(['branch', 'classRoom', 'teacher'])->orderBy('start_date', 'desc')->paginate(30);

        $teachers = resolve(TeacherRepository::class)->getListQuery(Auth::user())->get();

        return view('livewire.admin.courses.index', compact('courses', 'teachers'));
    }

    public function selectedTeacherId($teacherId)
    {
        $this->teacher_id = $teacherId;
    }
}
