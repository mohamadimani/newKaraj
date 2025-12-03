<?php

namespace App\Livewire\Users\OnlineCourses;

use App\Models\Discount;
use App\Models\OnlineCourse;
use Livewire\Component;

class Index extends Component
{
    public $search = '';

    public function render()
    {
        $discount = $this->getActiveDiscount();
        $onlineCourses = OnlineCourse::query();
        if ($this->search and strlen($this->search) > 2) {
            $onlineCourses = $onlineCourses->where('name', 'like', '%' . $this->search . '%');
        }
        $onlineCourses = $onlineCourses->active()->orderBy('id', 'desc')->paginate(30);

        return view('livewire.users.online-courses.index', compact('onlineCourses', 'discount'));
    }

    private function getActiveDiscount()
    {
        $discount =  Discount::where('is_online', true)->where('is_active', true)->where('discount_type', 'public')->where('available_from', '<=', now())->where('available_until', '>=', now())->whereNotNull('banner')->first();
        if ($discount and $discount->used_count >= $discount->usage_limit) {
            return null;
        }
        return $discount;
    }
}
