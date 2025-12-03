<?php

namespace App\Livewire\Admin\OnlineCourses;

use App\Models\OnlineCourse;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $onlineCourses = OnlineCourse::query();
        if (strlen($this->search) > 2) {
            $onlineCourses = $onlineCourses->where('name', 'like', '%' . $this->search . '%');
        }
        $onlineCourses = $onlineCourses->orderBy('id', 'desc')->paginate(30);
        return view('livewire.admin.online-courses.index', compact('onlineCourses'));
    }
}
