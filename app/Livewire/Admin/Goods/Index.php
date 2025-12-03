<?php

namespace App\Livewire\Admin\goods;

use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\Goods;
use App\Models\Teacher;
use App\Repositories\Goods\GoodsRepository;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    public $search;
    public $branch_id;
    public $class_room_id;
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $branches = Branch::active()->get();
        $classRooms = ClassRoom::active()->get();
        $teachers = Teacher::active()->get();

        $goods = resolve(GoodsRepository::class)->getListQuery(Auth::user());
        if (mb_strlen($this->search) > 1) {
            $search = trim($this->search);
            $goods->where('name', 'like', '%' . $search . '%');
        }
        if ($this->branch_id) {
            $goods->where('branch_id', $this->branch_id);
        }
        if ($this->class_room_id) {
            $goods->where('class_room_id', $this->class_room_id);
        }
        $goods = $goods->with('branch', 'classRoom', 'reports')->orderBy('id', 'DESC')->paginate(30);
        return view('livewire.admin.goods.index', compact('branches', 'classRooms', 'goods', 'teachers'));
    }
}
