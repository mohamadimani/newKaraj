<?php

namespace App\Livewire\Users\Courses;

use App\Models\Order;
use App\Models\CourseBasket;
use App\Models\Course;
use App\Models\Branch;
use App\Models\CourseOrderItem;
use App\Models\Discount as DiscountModel;
use App\Models\CourseRegister;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Show extends Component
{
    use WithPagination, LivewireAlert;

    public $fullPayDiscount = 5;
    public $payment_type = 'full_pay';
    public $course;
    public $discount_code;

    public function render()
    {
        $onlineBranch = Branch::where('name', 'online')->first();
        return view('livewire.users.courses.show', compact('onlineBranch'));
    }

    public function applyDiscountCode(Request $request)
    {
        $this->validate([
            'discount_code' => 'required|string|exists:discounts,code',
        ]);
        $discount = DiscountModel::where(['code' => $this->discount_code , 'is_online' => false])->first();

        if (!$discount) {
            $this->alert('error', 'این کد تخفیف وجود ندارد');
            return false;
        }

        if (strtotime($discount->available_from) > time()) {
            $this->alert('error', 'زمان استفاده از این تخفیف فرانرسیده است');
            return false;
        }
        if (strtotime($discount->available_until) < time()) {
            $this->alert('error', 'این کد تخفیف منقضی شده است');
            return false;
        }
        if ($discount->usage_limit <= $discount->used_count) {
            $this->alert('error', 'ظرفیت این کد تخفیف پر شده است');
            return false;
        }
        if ($discount->discount_type->value == 'user' and $discount->user_id != auth()->user()->id) {
            $this->alert('error', 'این کد تخفیف برای شما معتبر نیست');
            return false;
        }
        if (in_array($discount->discount_type->value, ['discount_type']) or $discount->is_online == 1) {
            $this->alert('error', 'این کد تخفیف برای دوره های آنلاین است');
            return false;
        }
        if (CourseBasket::where(['discount_id' => $discount->id, 'user_id' => auth()->user()->id])->exists()) {
            $this->alert('error', 'شما از این کد تخفیف استفاده کرده اید');
            return false;
        }

        session()->put('discount_id', $discount->id);
        session()->put('discount_code', $discount->code);
        session()->put('course_id', $this->course->id);
        session()->put('coursePriceWithDiscount', $this->caculateCourceDiscountAmount($this->course, $discount));
        $this->alert('success', 'کد تخفیف با موفقیت اعمال شد');
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

    public function addToBasket(Course $course)
    {
        if ($this->payment_type == null) {
            $this->alert('error', 'لطفا نحوه پرداخت را انتخاب کنید');
            return false;
        }
        if (CourseBasket::where(['course_id' => $course->id, 'user_id' => auth()->id()])->exists()) {
            $this->alert('error', 'این دوره در سبد خرید شما موجود است');
            return false;
        }

        if (CourseOrderItem::where(['course_id' => $course->id, 'user_id' => auth()->id()])->exists()) {
            $this->alert('error', 'این دوره در سفارش های شما موجود است');
            return false;
        }

        if (auth()->user()->student_id and CourseRegister::where(['course_id' => $course->id, 'student_id' => auth()->user()->student_id])->exists()) {
            $this->alert('error', 'شما قبلا در این دوره ثبت نام کرده اید');
            return false;
        }

        $discountId = session()->get('discount_id')  == $course->id ? session()->get('discount_id') : null;
        $courseBasket = CourseBasket::create([
            'course_id' => $course->id,
            'user_id' => auth()->id(),
            'discount_id' => $discountId ?? null,
            'created_by' => auth()->id(),
            'is_full_pay' => $this->payment_type == 'full_pay' ? true : false,
        ]);

        if ($courseBasket) {
            $this->alert('success', 'دوره با موفقیت به سبد خرید اضافه شد');
            return redirect()->route('user.course-baskets.index');
        }

        $this->alert('error', 'خطا در افزودن دوره به سبد خرید');
        return false;
    }
}
