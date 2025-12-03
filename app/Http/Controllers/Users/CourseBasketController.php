<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CourseBasket;
use App\Models\CourseOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class CourseBasketController extends Controller
{
    public $fullPayDiscount = 5;
    public function index()
    {
        return view('users.course-baskets.index');
    }

    public function checkout()
    {
        $user = auth()->user();
        $totalAmount = $this->calculateTotalAmount();

        if ($user->courseBaskets->isEmpty()) {
            return redirect()->back()->with('error', 'سبد خرید شما خالی می باشد');
        }

        $courseOrder = CourseOrder::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'created_by' => $user->id,
        ]);

        foreach ($user->courseBaskets as $item) {
            $coursePrice = 0;
            $discountAmount = 0;
            $finalAmount = 0;
            $discountId = null;
            if ($item->is_full_pay) {
                $coursePrice = $item->course->price;
                $courseFullPayPrice = $coursePrice - ($item->course->price * $this->fullPayDiscount / 100);
                if ($item->discount and strtotime($item->discount->available_until)  >= time()) {
                    $discountAmount = $this->caculateCourceDiscountAmount($item->course, $item->discount);
                    $finalAmount = $courseFullPayPrice - $discountAmount;
                    $discountId = $item->discount->id;
                } else {
                    $finalAmount = $courseFullPayPrice;
                }
            } else {
                $coursePrice = $this->getOnlineBranchMinPay();
                $finalAmount = $coursePrice;
            }

            $courseOrderItem = $courseOrder->courseOrderItems()->create([
                'user_id' => $user->id,
                'order_id' => $courseOrder->id,
                'course_id' => $item->course_id,
                'discount_id' => $discountId,
                'teacher_id' => $item->course->teacher_id,
                'created_by' => $user->id,
                'is_full_pay' => $item->is_full_pay,
                'amount' => $coursePrice,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ]);
        }
        $user->courseBaskets()->delete();
        return redirect()->route('user.course-orders.show', $courseOrder->id);
    }

    private function calculateTotalAmount()
    {
        $totalAmount = 0;
        foreach (auth()->user()->courseBaskets as $item) {
            if ($item->is_full_pay) {
                $coursePrice = $item->course->price - ($item->course->price * $this->fullPayDiscount / 100);
                if ($item->discount and strtotime($item->discount->available_until)  >= time()) {
                    $discountAmount = $this->caculateCourceDiscountAmount($item->course, $item->discount);
                    $totalAmount += $coursePrice - $discountAmount;
                } else {
                    $totalAmount += $coursePrice;
                }
            } else {
                $totalAmount += $this->getOnlineBranchMinPay();
            }
        }
        return $totalAmount;
    }

    public function caculateCourceDiscountAmount($course, $discount)
    {
        if ($discount->amount_type->value == 'fixed') {
            $percent = ($discount->amount  / $course->price) * 100;
            $percent = round($percent, 5, true);
            return ($course->price * $percent) / 100;
        }
        if ($discount->amount_type->value == 'percentage') {
            return ($course->price * $discount->amount) / 100;
        }
        return 0;
    }

    private function getOnlineBranchMinPay()
    {
        return Branch::where('name', 'online')->first()->minimum_pay;
    }
}
