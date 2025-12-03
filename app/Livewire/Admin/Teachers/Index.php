<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\Teacher;
use App\Repositories\User\TeacherRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Index extends Component
{
    use LivewireAlert;

    public $teacher;
    public $search = '';
    protected $listeners = ['delete'];

    public function render()
    {
        $teacherRepository = resolve(TeacherRepository::class);
        $teachers = $teacherRepository->getListQuery();

        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $teachers->whereHas('user', function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw('CONCAT(first_name," ",last_name)'), 'LIKE', "%$search%")
                    ->orWhere('mobile', 'like', '%' . $search . '%');
            });
        }

        $teachers = $teachers->with('user')->orderBy('id', 'desc')->paginate(30);

        return view('livewire.admin.teachers.index', compact('teachers'));
    }

    public function updateStatus(Teacher $teacher, $status)
    {
        $teacher->is_active = $status;
        if ($teacher->save()) {
            $this->alert('success', __('public.messages.successfully_done'));
        } else {
            $this->alert('error', __('public.messages.something_went_wrong'));
        }
    }

    public function deleteTeacher(Teacher $teacher)
    {
        $this->confirm(__('public.messages.confirm_delete'), [
            'onConfirmed' => 'delete',
        ]);

        $this->teacher = $teacher;
    }

    public function delete()
    {
        $this->teacher->deleted_by = Auth::id();
        $this->teacher->save();
        $this->teacher->delete();

        $this->alert('success', __('public.messages.successfully_done'));
    }
}
