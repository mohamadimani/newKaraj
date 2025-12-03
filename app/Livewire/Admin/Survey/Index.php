<?php

namespace App\Livewire\Admin\Survey;

use App\Models\CourseRegister;
use App\Models\Survey;
use App\Repositories\User\TeacherRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $courseRegisterId;
    public $search;
    public $teacherId;


    public function render()
    {
        $survey = Survey::with('courseRegister')->with('courseRegister.course');
        $search = trim($this->search);

        if (mb_strlen($this->search) > 1) {
            $survey = $survey->whereHas('user', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });
            $survey = $survey->orWhereHas('courseRegister.course', function ($query) use ($search) {
                return $query->where('courses.title', 'LIKE', "%$search%");
            });
        }

        if ($this->teacherId) {
            $teacherId = $this->teacherId;
            $survey = $survey->whereHas('courseRegister.course', function ($query) use ($teacherId) {
                return $query->where('teacher_id', $teacherId);
            });
        }

        $survey = $survey->orderBy('id', 'DESC')->paginate(30);
        $answers = [
            1 => ['title' => 'بد', 'color' => 'danger'],
            2 => ['title' => 'ضعیف', 'color' => 'danger'],
            3 => ['title' => 'متوسط', 'color' => 'warning'],
            4 => ['title' => 'خوب', 'color' => 'info'],
            5 => ['title' => 'عالی', 'color' => 'success'],
        ];

        $teachers = resolve(TeacherRepository::class)->getListQuery(Auth::user())->get();
        return view('livewire.admin.survey.index', compact('survey', 'answers', 'teachers'));
    }

    public function setTeacherId($teacherId)
    {
        $this->teacherId = $teacherId;
    }
}
