<?php

namespace App\Livewire\Admin\Reports\CourseRegisterChangeLog;

use App\Models\CourseRegisterChangeLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;

    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    public function render()
    {
        $courseRegisterLogs = CourseRegisterChangeLog::query()->with('user');

        $search = trim($this->search);
        if (mb_strlen($search) > 1) {
            $courseRegisterLogs = $courseRegisterLogs->whereHas('user', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });

            $courseRegisterLogs = $courseRegisterLogs->orWhereHas('course', function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%');
            });

            $courseRegisterLogs = $courseRegisterLogs->orWhereHas('courseRegister.student.user', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });

            $courseRegisterLogs =  $courseRegisterLogs->orWhere('description', 'LIKE', '%' . $search . '%');
        }

        $courseRegisterLogs =  $courseRegisterLogs->orderBy('created_at', 'desc')->paginate(30);

        return view('livewire.admin.reports.course-register-change-log.index', compact('courseRegisterLogs'));
    }
}
