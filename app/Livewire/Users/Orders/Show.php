<?php

namespace App\Livewire\Users\Orders;

use App\Enums\OnlinePayment\StatusEnum;
use App\Enums\Payment\StatusEnum as PaymentStatusEnum;
use App\Models\Discount;
use App\Models\OnlinePayment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\SpotPlayer\SpotPlayerService;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Show extends Component
{
    use LivewireAlert;
    public $discount_code;
    public $reference_code;
    public $order;
    public function render()
    {
        if ($this->order->reference_code > 0) {
            $this->reference_code = $this->order->reference_code;
        }
        if ($this->order->discount_id > 0) {
            $this->discount_code = $this->order->discount->code;
        }
        return view('livewire.users.orders.show');
    }

    public function applyDiscountCode()
    {
        $this->validate([
            'discount_code' => 'required|string|exists:discounts,code',
        ]);
        $discount = Discount::where(['code' => $this->discount_code , 'is_online' => true])->first();

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
        if (in_array($discount->discount_type->value, ['profession', 'course'])) {
            $this->alert('error', 'این کد تخفیف برای دوره های حضوری است');
            return false;
        }
        if ($this->order->discount_id > 0) {
            $this->alert('error', 'برای این سفارش قبلا تخفیف اعمال شده است');
            return false;
        }
        if (Order::where(['discount_id' => $discount->id, 'user_id' => auth()->user()->id, 'is_active' => true])->exists()) {
            $this->alert('error', 'شما از این کد تخفیف استفاده کرده اید');
            return false;
        }

        $this->order->discount_id = $discount->id;
        $this->order->discount_amount = $this->caculateOrderDiscountAmount($discount);
        $this->order->final_amount = $this->order->total_amount - $this->order->discount_amount;
        if ($this->order->save()) {
            // caculate Order Item Discount Amount
            foreach ($this->order->orderItems as $orderItem) {
                $orderItem->discount_id = $discount->id;
                $orderItem->discount_amount = $this->caculateOrderItemDiscountAmount($orderItem, $discount, $this->order->total_amount);
                $orderItem->final_amount = $orderItem->total_amount - $orderItem->discount_amount;

                $orderItem->teacher_percent =  ($orderItem->final_amount * $orderItem->onlineCourse->percent) / 100;
                $orderItem->save();
            }
            $this->alert('success', 'کد تخفیف با موفقیت اعمال شد');
        } else {
            $this->alert('error', 'مشکلی در اعمال کد تخفیف به وجود آمده است');
        }
    }

    public function applyReferenceCode()
    {
        $this->validate([
            'reference_code' => 'required|string|exists:users,reference_code',
        ]);
        $referenceUser = User::where(['reference_code' => $this->reference_code])->first();

        if (!$referenceUser) {
            $this->alert('error', 'کاربری با این کد معرف وجود ندارد');
            return false;
        }

        if ($referenceUser and $referenceUser->id == user()->id) {
            $this->alert('error', 'شما نمیتوانید از کد معرفی خود استفاده کنید');
            return false;
        }

        $this->order->reference_code = $this->reference_code;
        if ($this->order->save()) {
            $this->alert('success', 'کد معرف با موفقیت اعمال شد');
        } else {
            $this->alert('error', 'مشکلی در اعمال کد معرف به وجود آمده است');
        }
    }

    public function caculateOrderDiscountAmount(Discount $discount)
    {
        if ($discount->amount_type->value == 'fixed') {
            return $discount->amount;
        }
        if ($discount->amount_type->value == 'percentage') {
            return ($this->order->total_amount * $discount->amount) / 100;
        }
        return 0;
    }

    public function caculateOrderItemDiscountAmount($orderItem, Discount $discount, $orderTotalAmount)
    {
        if ($discount->amount_type->value == 'fixed') {
            $percent = ($discount->amount  / $orderTotalAmount) * 100;
            $percent = round($percent, 5, true);
            return ($orderItem->total_amount * $percent) / 100;
        }
        if ($discount->amount_type->value == 'percentage') {
            return ($orderItem->total_amount * $discount->amount) / 100;
        }
        return 0;
    }

    public function payByWallet()
    {
        if ($this->order->user->wallet <= 0) {
            return $this->alert('error', 'موجودی کیف پول کافی نیست');
        }
        $paymentDescription = ' - پرداخت با کیف پول توسط کارآموز- ';

        $paidAmountSum = $this->order->onlinePayments()->where('pay_confirm', true)->sum('paid_amount');
        $payAmount = $this->order->final_amount - $paidAmountSum;

        $paid_amount = $payAmount > $this->order->user->wallet ? $this->order->user->wallet :  $payAmount;

        DB::beginTransaction();
        try {
            $onlinePeyment = OnlinePayment::create([
                'user_id' => $this->order->user_id,
                'order_id' => $this->order->id,
                'amount' => $payAmount,
                'paid_amount' => $paid_amount,
                'status' => StatusEnum::PAID->value,
                'pay_confirm' => true,
                'description' =>  $paymentDescription,
                'created_by' => FIDAR_AI(),
            ]);
            $payment = Payment::create([
                'payment_method_id' => 16,
                'paid_amount' => $paid_amount,
                'description' =>   $paymentDescription,
                'pay_date' =>  Verta(now()->timestamp)->format('Y/m/d H:i:s'),
                'paymentable_type' => Order::class,
                'paymentable_id' => $this->order->id,
                'branch_id' => $this->order->user->clue->branch_id ?? 7,
                'user_id' => $this->order->user_id,
                'created_by' => FIDAR_AI(),
                'is_wallet_pay' =>  true,
                'status' => PaymentStatusEnum::VERIFIED
            ]);

            $description = 'پرداخت از کیف پول برای دوره آنلاین توسط کارآموز';
            withdrawTransaction($payment, $description, 1);

            if ($payAmount <= 0) {
                $this->checkout($this->order);
            }
            DB::commit();
            return $this->alert('success', 'با موفقیت پرداخت شد');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->alert('error', 'مشکلی در پرداخت به وجود آمده است');
        }
    }

    public function checkout(Order $order)
    {

        foreach ($order->orderItems as $orderItem) {
            try {
                $License = SpotPlayerService::license($order->user->full_name, [$orderItem->spot_key], [$order->user->mobile], false);
                $orderItem->license_key = $License['key'];
                $orderItem->license_url = $License['url'];
                $orderItem->license_id = $License['_id'];
            } catch (Exception $e) {
                echo ($e->getMessage());
            }
            $orderItem->pay_date = time();
            $orderItem->save();
            $orderItem->onlineCourse->registered_count = $orderItem->onlineCourse->registered_count + 1;
            $orderItem->onlineCourse->save();
        }
        $order->payment_status = 'paid';
        $order->pay_date = time();
        $order->save();
        // ===== update discount  used count  =====
        if ($order->discount_id > 0) {
            $order->discount->used_count = $orderItem->discount->used_count + 1;
            $order->discount->save();
        }
        return true;
    }
}
