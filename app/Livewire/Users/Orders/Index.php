<?php

namespace App\Livewire\Users\Orders;

use App\Models\CourseOrder;
use App\Models\Order;
use Livewire\Component;

class Index extends Component
{
    public $search = '';

    public function render()
    {
        $onlineOrders = Order::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->with('orderItems')->paginate(30);
        $courseOrders = CourseOrder::where('user_id', auth()->user()->id)->whereHas('courseOrderItems')->orderBy('id', 'desc')->with('courseOrderItems')->paginate(30);

        return view('livewire.users.orders.index', compact('onlineOrders', 'courseOrders'));
    }
}
