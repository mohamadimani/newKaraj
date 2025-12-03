<?php

namespace App\Livewire\Admin\OnlineCourseBaskets;

use App\Models\OnlineCourseBasket;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $search;
    public function render()
    {
        $onlineCourseBasketUsers = OnlineCourseBasket::with(['onlineCourse', 'user']);
        if (strlen($this->search) > 2) {
            $onlineCourseBasketUsers = $onlineCourseBasketUsers->whereHas('user', function ($query) {
                $query->where('users.first_name', 'like', "%$this->search%")
                    ->orWhere('users.last_name', 'like', "%$this->search%")
                    ->orWhere(DB::raw('CONCAT(users.first_name, " ", users.last_name)'), 'like', "%$this->search%")
                    ->orWhere('users.mobile', 'like', "%$this->search%");
            });
        }
        $onlineCourseBasketUsers = $onlineCourseBasketUsers->whereHas('onlineCourse')->select('user_id')->distinct()->orderBy('id', 'desc')->paginate(30);

        return view('livewire.admin.online-course-baskets.index', compact('onlineCourseBasketUsers'));
    }
}
