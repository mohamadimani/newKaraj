<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OnlinePayment\StatusEnum;
use App\Enums\Payment\StatusEnum as PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\OnlinePayment;
use App\Models\Order;
use App\Models\OrderItemChangeLog;
use App\Models\Payment;
use App\Models\PaymentImage;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Services\SpotPlayer\SpotPlayerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnlineCourseOrderController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Order::class);
        return view('admin.online-course-orders.index');
    }

    public function registers()
    {
        return view('admin.online-course-orders.registers');
    }

    public function show(Order $order)
    {
        Gate::authorize('show', Order::class);
        $totalPaidAmount = $this->getOrderPaidAmount($order);
        $paymentMethods = PaymentMethod::active()->get();
        return view('admin.online-course-orders.show', compact('order', 'totalPaidAmount', 'paymentMethods'));
    }

    public function store(Request $request, User $user)
    {
        // Gate::authorize('store', Order::class);

        $totalAmount = $this->calculateTotalAmount($user);

        if ($user->baskets->isEmpty()) {
            return redirect()->back()->with('error', 'سبد خرید این کاربر خالی می باشد');
        }
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'final_amount' => $totalAmount,
            'payment_status' => 'pending',
            'created_by' => auth()->user()->id,
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
                'created_by' => auth()->user()->id,
                'teacher_id' => $basket->onlineCourse->teacher_id,
                'teacher_percent' => $teacherPercent,
                'spot_key' => $basket->onlineCourse->spot_key,
            ]);
        }
        $user->baskets()->delete();
        return redirect()->route('online-course-orders.show', $order->id);
    }

    private function calculateTotalAmount($user)
    {
        $totalAmount = 0;
        foreach ($user->baskets as $item) {
            if ($item->onlineCourse->discount_amount > 0 and  intval($item->onlineCourse->discount_start_at) <= time() and intval($item->onlineCourse->discount_expire_at) >= time()) {
                $totalAmount += $item->onlineCourse->discount_amount;
            } else {
                $totalAmount += $item->onlineCourse->amount;
            }
        }
        return $totalAmount;
    }

    public function pay(Request $request, Order $order)
    {
        $totalPaidAmount = $this->getOrderPaidAmount($order);
        $request->validate([
            'paid_amount' => 'required|numeric|min:1|max:' . ($order->final_amount - $totalPaidAmount),
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_description' => ['required'],
        ]);

        if (!in_array($request->payment_method_id, [16])) {
            $request->validate([
                'pay_date' => ['required'],
                'paid_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2000',
            ]);
        }

        if ($order->user->first_name == null or $order->user->full_name == null or $order->user->mobile == null) {
            return redirect()->back()->with('error', 'لطفا اطلاعات کاربر را تکمیل کنید');
        }

        if (in_array($request->payment_method_id, [16]) and $request->paid_amount > $order->user->wallet) {
            return redirect()->back()->with('error', 'مبلغ پرداختی بیشتر از موجودی کیف پول می باشد!')->withInput();
        }

        $paymentDescription = null;
        $pay_confirm = false;
        $onlinePaymentStatus = StatusEnum::PENDING->value;

        if (in_array($request->payment_method_id, [16])) {
            $paymentDescription = ' - پرداخت با کیف پول- ';
            $pay_confirm = true;
            $onlinePaymentStatus = StatusEnum::PAID->value;
        }

        DB::beginTransaction();
        try {
            $onlinePeyment = OnlinePayment::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'amount' => $order->final_amount,
                'paid_amount' => $request->paid_amount,
                'status' => $onlinePaymentStatus,
                'pay_confirm' => $pay_confirm,
                'description' => '- پرداخت توسط مشاور - ' . $paymentDescription,
                'created_by' => Auth::id(),
            ]);
            if ($onlinePeyment) {
                if (in_array($request->payment_method_id, [16])) {
                    $payment = Payment::create([
                        'payment_method_id' => $request->payment_method_id,
                        'paid_amount' => $request->paid_amount,
                        'description' => $request->payment_description . ' ' . $paymentDescription,
                        'pay_date' => $request->pay_date ?? Verta(now()->timestamp)->format('Y/m/d H:i:s'),
                        'paymentable_type' => Order::class,
                        'paymentable_id' => $order->id,
                        'branch_id' => $order->user->clue->branch_id,
                        'user_id' => $order->user_id,
                        'created_by' => Auth::id(),
                        'is_wallet_pay' =>  true,
                        'status' => PaymentStatusEnum::VERIFIED
                    ]);

                    $description = 'برداشت بابت پرداخت از کیف پول برای دوره آنلاین';
                    withdrawTransaction($payment, $description, 1);
                } else {
                    $payment = Payment::create([
                        'payment_method_id' => $request->payment_method_id,
                        'paid_amount' => $request->paid_amount,
                        'description' => $request->payment_description,
                        'pay_date' => $request->pay_date ?? Verta(now()->timestamp)->format('Y/m/d H:i:s'),
                        'paymentable_type' => Order::class,
                        'paymentable_id' => $order->id,
                        'branch_id' => $order->user->clue->branch_id,
                        'user_id' => $order->user_id,
                        'created_by' => Auth::id(),
                    ]);
                }

                if ($request->paid_image) {
                    $imageName = Verta(now()->timestamp)->format('m') . '/' . SaveImage($request->paid_image, 'payments/bill/' . Verta(now()->timestamp)->format('m') . '/');
                    PaymentImage::create([
                        'payment_id' => $payment->id,
                        'title' => $imageName,
                        'description' => $request->payment_description,
                        'create_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'پرداخت با موفقیت ثبت شد');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function checkout(Order $order)
    {
        if ($order->final_amount - $this->getOrderPaidAmount($order) == 0 and $order->payment_status == 'paid') {
            return redirect()->back()->with('error', 'سفارش قبلا تسویه شده است');
        }
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
        return redirect()->route('online-course-orders.show', $order->id)->with('success', 'تسویه سفارش با موفقیت انجام شد');
    }

    private function getOrderPaidAmount($order)
    {
        $orderPayments = Payment::where([
            'paymentable_type' => Order::class,
            'paymentable_id' => $order->id,
            'status' => 'verified',
        ])->get();
        $totalPaidAmount = 0;
        foreach ($orderPayments as $orderPayment) {
            $totalPaidAmount += $orderPayment->paid_amount;
        }
        return $totalPaidAmount;
    }

    public function updateAmount(Request $request, OrderItem $orderItem)
    {
        $request->validate([
            'new_amount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'min:5'],
        ]);

        $oldValue = $orderItem->amount;
        $newValue = $request->new_amount;

        $totalPaymentAmount = $this->getOrderPaidAmount($orderItem->order);
        $finalAmount = $orderItem->order->orderItems->sum('final_amount');
        $finalAmount = ($finalAmount - $orderItem->final_amount) + $request->new_amount;

        $orderItem->amount = $request->new_amount;
        $orderItem->total_amount = $request->new_amount;
        $orderItem->final_amount = $request->new_amount;
        $orderItem->change_amount_description = $request->description;

        if ($totalPaymentAmount > $finalAmount) {
            return redirect()->back()->with('error', 'مبلغ پرداخت شده از مبلغ سفارش بیشتر است');
        }
        if ($orderItem->save()) {
            $orderItem->order->final_amount = $orderItem->order->total_amount = $finalAmount;
            $orderItem->order->save();

            $orderPayment = OnlinePayment::where('order_id', $orderItem->order_id)
                ->where('user_id', $orderItem->user_id)
                ->where('status', 'pending')
                ->where('amount', $oldValue)
                ->first();
            if ($orderPayment) {
                $orderPayment->amount = $newValue;
                $orderPayment->save();
            }

            OrderItemChangeLog::addLog($orderItem, 'amount', 'مبلغ', $oldValue, $newValue, 'تغییر قیمت سفارش');
            return redirect()->back()->with('success', 'تغییرات با موفقیت ثبت شد');
        }
        return redirect()->back()->with('error', 'تغییرات با مشکل مواجه شد');
    }

    public function deleteItem(Order $order, OrderItem $orderItem)
    {
        $orderItem->deleted_by = Auth::id();
        if ($orderItem->save()) {
            $orderItem->delete();
            $order->final_amount = $order->total_amount = $order->final_amount - $orderItem->final_amount;
            $order->save();
            if ($order->orderItems->count() == 0) {
                $order->deleted_by = Auth::id();
                $order->save();
                $order->delete();
                if ($order->payment_id > 0) {
                    $onlinePayment = OnlinePayment::where('id', $order->payment_id)->first();
                    $onlinePayment->deleted_by = Auth::id();
                    $onlinePayment->save();
                    $onlinePayment->delete();
                }
                return redirect()->route('online-course-orders.index')->with('success', 'حذف با موفقیت انجام شد');
            }
            return redirect()->back()->with('success', 'حذف با موفقیت انجام شد');
        }
        return redirect()->back()->with('error', 'حذف با مشکل مواجه شد');
    }
}
