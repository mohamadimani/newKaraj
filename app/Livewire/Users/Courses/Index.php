<?php

namespace App\Livewire\Users\Courses;

use App\Models\Course;
use App\Models\Profession;
use Livewire\Component;

class Index extends Component
{
    public $search;
    public $selected_profession;

    public function render()
    {
        $courses = Course::query();
        if ($this->search) {
            $courses = $courses->where('title', 'LIKE', "%$this->search%");
        }
        if ($this->selected_profession > 0) {
            $courses = $courses->where('profession_id', $this->selected_profession);
        }
        $courses = $courses->active()->where('start_date', '>=', now()->subDay())->paginate(30);

        $professions = Profession::active()->get();
        return view('livewire.users.courses.index', compact('courses', 'professions'));
    }

    public function setProfessionIdValue($value)
    {
        $this->selected_profession = $value;
    }
}
