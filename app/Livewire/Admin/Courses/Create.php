<?php

namespace App\Livewire\Admin\Courses;

use App\Enums\Course\CourseTypeEnum;
use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\Profession;
use App\Models\Teacher;
use App\Repositories\Profession\ProfessionRepository;
use App\Repositories\User\TeacherRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $profession_id;
    public $teacher_id;
    public $branch_id;
    public $class_room_id;
    public $course_type;
    public $title;

    public $capacity;
    public $price;
    public $duration_hours;

    public $professions;
    public $teachers;
    public $branches;
    public $classRooms;

    public function render()
    {
        return view('livewire.admin.courses.create');
    }

    public function mount()
    {
        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $this->professions = $professions->active()->get();


        $this->teachers = resolve(TeacherRepository::class)->getListQuery(Auth::user())->active()->with('user')->get();;
        $this->branches = Branch::active()->get();
        $this->classRooms = ClassRoom::active()->with('branch')->get();

        $this->profession_id = old('profession_id');
        $this->setProfessionValue($this->profession_id, old('course_type', CourseTypeEnum::PUBLIC->value));
        $this->course_type = old('course_type', CourseTypeEnum::PUBLIC->value);
        $this->title = old('title');
        $this->teacher_id = old('teacher_id');
        $this->branch_id = old('branch_id');
        $this->class_room_id = old('class_room_id');
    }

    public function setProfessionValue(null|int|string $professionId, string $courseType)
    {
        if ($professionId && $profession = Profession::find($professionId)) {
            $isPublic = $courseType === CourseTypeEnum::PUBLIC->value;
            $this->profession_id = $profession->id;
            $this->teachers = $profession->teachers;
            $this->branches = $profession->branches ?? $profession->teachers->branch;

            $this->classRooms = $profession->classRooms;
            $this->capacity = $isPublic ? $profession->public_capacity : $profession->private_capacity;
            $this->price = $isPublic ? number_format($profession->public_price) : number_format($profession->private_price);
            $this->duration_hours = $isPublic ? $profession->public_duration_hours : $profession->private_duration_hours;
        }
    }
}
