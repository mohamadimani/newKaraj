<?php

namespace App\Livewire\Users\CourseBaskets;

use App\Models\Branch;
use App\Models\FamiliarityWay;
use App\Models\Course;
use App\Models\CourseBasket;
use App\Models\OrderItem;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Index extends Component
{
    public $first_name;
    public $last_name;
    public $familiarity_way_id;

    public $fullPayDiscount = 5;
    public $courseBasket;
    protected $listeners = ['delete'];
    use LivewireAlert;
    public function render()
    {
        $courseBaskets =  CourseBasket::where('user_id', auth()->user()->id)->with(['course', 'discount'])->get();
        $familiarityWays = FamiliarityWay::all();
        $onlineBranch = Branch::where('name', 'online')->first();
        return view('livewire.users.course-baskets.index', compact('courseBaskets', 'familiarityWays', 'onlineBranch'));
    }

    public function mount()
    {
        $this->first_name = auth()->user()->first_name;
        $this->last_name = auth()->user()->last_name;
        $this->familiarity_way_id = auth()->user()->clue->familiarity_way_id;
    }
    public function destroy(CourseBasket $courseBasket)
    {
        $this->confirm(__('public.messages.confirm_delete'), [
            'onConfirmed' => 'delete',
        ]);
        $this->courseBasket = $courseBasket;
    }

    public function delete()
    {
        $this->courseBasket->delete();
        $this->alert('success', __('public.messages.successfully_deleted'));
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
        return redirect()->route('user.course-baskets.checkout');
    }
}
