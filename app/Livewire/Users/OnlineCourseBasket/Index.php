<?php

namespace App\Livewire\Users\OnlineCourseBasket;

use App\Models\FamiliarityWay;
use App\Models\OnlineCourse;
use App\Models\OnlineCourseBasket;
use App\Models\OrderItem;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Index extends Component
{

    public $first_name;
    public $last_name;
    public $familiarity_way_id;
    public $onlineCourseId;
    public $quantity = 1;
    public $onlineCourseBasket;
    protected $listeners = ['delete'];
    use LivewireAlert;
    public function render()
    {
        $onlineCourseBaskets =  OnlineCourseBasket::where('user_id', auth()->user()->id)->get();
        $onlineCourses = OnlineCourse::active()->orderBy('id', 'desc')->get();
        $familiarityWays = FamiliarityWay::all();
        return view('livewire.users.online-course-basket.index', compact('onlineCourseBaskets', 'onlineCourses', 'familiarityWays'));
    }

    public function mount()
    {
        $this->first_name = auth()->user()->first_name;
        $this->last_name = auth()->user()->last_name;
        $this->familiarity_way_id = auth()->user()->clue->familiarity_way_id;
    }
    public function destroy(OnlineCourseBasket $onlineCourseBasket)
    {
        $this->confirm(__('public.messages.confirm_delete'), [
            'onConfirmed' => 'delete',
        ]);
        $this->onlineCourseBasket = $onlineCourseBasket;
    }

    public function delete()
    {
        $this->onlineCourseBasket->delete();
        $this->alert('success', __('public.messages.successfully_deleted'));
    }

    public function store()
    {
        $this->validate([
            'onlineCourseId' => 'required|exists:online_courses,id',
        ]);
      
        $onlineCourseItem = OrderItem::where('user_id', auth()->user()->id)->where('online_course_id', $this->onlineCourseId)->first();
        if ($onlineCourseItem && $onlineCourseItem->license_key != null) {
            $this->alert('error', 'شما قبلا این دوره را خریداری کرده اید');
            return true;
        }
        if ($onlineCourseItem && $onlineCourseItem->license_key == null) {
            $this->alert('error', 'این دوره در   سفارش های پرداخت نشده شما موجود است');
            return true;
        }
        if (OnlineCourseBasket::where('user_id', auth()->user()->id)->where('online_course_id', $this->onlineCourseId)->first()) {
            $this->alert('error', 'این دوره در سبد خرید موجود است');
            return true;
        }

        OnlineCourseBasket::create([
            'user_id' => auth()->user()->id,
            'online_course_id' => $this->onlineCourseId,
        ]);
        $this->alert('success', 'با موفقیت ثبت شد');
    }

    public function setOnlineCourseId($value)
    {
        $this->onlineCourseId = $value;
    }

    public function updateClueInfo()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'familiarity_way_id' => 'required|exists:familiarity_ways,id',
        ]);
        $user = auth()->user();
        $result = $user->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ]);
        if (!$result) {
            $this->alert('error', 'خطایی رخ داده است');
            return;
        }
        $user->clue()->update([
            'familiarity_way_id' => $this->familiarity_way_id,
        ]);
        $this->redirectToCheckout();
    }

    public function redirectToCheckout()
    {
        return redirect()->route('user.online-course-baskets.checkout');
    }
}
