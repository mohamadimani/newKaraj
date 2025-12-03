<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourseBasket;
use App\Models\Order;
use Illuminate\Http\Request;

class OnlineCourseBasketController extends Controller
{
    public function index()
    {
        return view('users.online-course-baskets.index');
    }

    public function checkout()
    {
        $user = auth()->user();
        $totalAmount = $this->calculateTotalAmount();

        if ($user->baskets->isEmpty()) {
            return redirect()->back()->with('error', 'سبد خرید شما خالی می باشد');
        }
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'final_amount' => $totalAmount,
            'payment_status' => 'pending',
            'created_by' => FIDAR_AI(),
        ]);

        foreach ($user->baskets as $basket) {
            $amount = 0;
            if ($basket->onlineCourse->discount_amount > 0 and  intval($basket->onlineCourse->discount_start_at) <= time() and intval($basket->onlineCourse->discount_expire_at) >= time()) {
                $amount = $basket->onlineCourse->discount_amount;
            } else {
                $amount = $basket->onlineCourse->amount;
            }
            $teacherPercent = ($amount * $basket->onlineCourse->percent) / 100;
            $orderItem = $order->orderItems()->create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'online_course_id' => $basket->online_course_id,
                'quantity' => $basket->quantity,
                'amount' => $amount,
                'total_amount' => $amount,
                'final_amount' => $amount,
                'created_by' =>  FIDAR_AI(),
                'teacher_id' => $basket->onlineCourse->teacher_id,
                'teacher_percent' => $teacherPercent,
                'spot_key' => $basket->onlineCourse->spot_key,
            ]);
        }
        $user->baskets()->delete();
        return redirect()->route('user.orders.show', $order->id);
    }

    private function calculateTotalAmount()
    {
        $userBasket = auth()->user()->baskets;
        $totalAmount = 0;
        foreach ($userBasket as $item) {
            if ($item->onlineCourse->discount_amount > 0 and  intval($item->onlineCourse->discount_start_at) <= time() and intval($item->onlineCourse->discount_expire_at) >= time()) {
                $totalAmount += $item->onlineCourse->discount_amount;
            } else {
                $totalAmount += $item->onlineCourse->amount;
            }
        }
        return $totalAmount;
    }
}
