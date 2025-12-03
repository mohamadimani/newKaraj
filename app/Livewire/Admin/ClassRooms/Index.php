<?php

namespace App\Livewire\Admin\ClassRooms;



use App\Models\ClassRoom;
use App\Models\Branch;
use App\Repositories\ClassRoom\ClassRoomRepository;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $classRoom;
    protected $listeners = ['delete'];


    public function render()
    {
        $search = trim($this->search);
        $classRoomRepository = resolve(ClassRoomRepository::class);
        $classRooms = $classRoomRepository->getListQuery(Auth::user());
        if (mb_strlen($search) > 1) {
            $classRooms = $classRooms->where('name', 'like', "%$search%");

            $classRooms = $classRooms->orWhereHas('professions', function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
            });
        }
        $classRooms = $classRooms->with('branch')
            ->paginate(30);

        return view('livewire.admin.class-rooms.index', compact('classRooms'));
    }

    public function deleteClassRoom(ClassRoom $classRoom)
    {
        $this->confirm(__('class_rooms.are_you_sure_to_delete'), [
            'onConfirmed' => 'delete',
        ]);
        $this->classRoom = $classRoom;
    }

    public function delete()
    {
        $this->classRoom->deleted_by = Auth::id();
        $this->classRoom->save();
        if ($this->classRoom->delete()) {
            $this->alert('success', __('class_rooms.successfully_deleted'));
        } else {
            $this->alert('error', __('class_rooms.failed_to_delete'));
        }
    }

    public function updateStatus(ClassRoom $classRoom, $status)
    {
        $classRoom->is_active =  $status;
        $classRoom->save();
        $this->alert('success', $classRoom->is_active ? __('class_rooms.successfully_activated') : __('class_rooms.successfully_inactivated'));
    }
}
