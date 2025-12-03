<?php

namespace App\Livewire\Admin\Courses;

use App\Models\Profession;
use App\Repositories\Profession\ProfessionRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Update extends Component
{
    public $course;

    public $profession_id;
    public $teacher_id;
    public $branch_id;
    public $class_room_id;

    public $capacity;
    public $price;
    public $duration_hours;
    public $course_type;
    public $title;

    public $professions;
    public $teachers;
    public $branches;
    public $classRooms;

    public function render()
    {
        return view('livewire.admin.courses.edit');
    }

    public function mount()
    {
        $profession = Profession::find(old('profession_id', $this->course->profession_id));
        $this->setProfessionValue($profession->id, old('course_type', $this->course->course_type));
        $this->course_type = old('course_type', $this->course->course_type);
        $this->title = old('title', $this->course->title);
        $this->profession_id = $profession->id;
        $this->teacher_id = old('teacher_id', $this->course->teacher_id);
        $this->branch_id = old('branch_id', $this->course->branch_id);
        $this->class_room_id = old('class_room_id', $this->course->class_room_id);

        $professionRepository = resolve(ProfessionRepository::class);
        $professions = $professionRepository->getListQuery(Auth::user());
        $this->professions = $professions->active()->get();

        $this->capacity = old('capacity', $this->course->capacity);
        $this->price = old('price', number_format($this->course->price));
        $this->duration_hours = old('duration_hours', $this->course->duration_hours);
    }

    public function setProfessionValue(int|string $professionId, string $courseType)
    {
        if ($professionId && $profession = Profession::find($professionId)) {
            $isPublic = $courseType === 'public';
            $this->profession_id = old('profession_id', $profession->id);
            $this->teachers = $profession->teachers;
            $this->branches = $profession->branches ?? $profession->teachers->branch;

            $this->classRooms = $profession->classRooms;
            $this->capacity = old('capacity', $isPublic ? $profession->public_capacity : $profession->private_capacity);
            $this->price = old('price', $isPublic ? number_format($profession->public_price) : number_format($profession->private_price));
            $this->duration_hours = old('duration_hours', $isPublic ? $profession->public_duration_hours : $profession->private_duration_hours);
        }
    }
}
