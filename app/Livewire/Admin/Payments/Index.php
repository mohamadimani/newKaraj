<?php

namespace App\Livewire\Admin\Payments;

use App\Enums\OnlinePayment\StatusEnum as OnlinePaymentStatusEnum;
use App\Enums\Payment\StatusEnum;
use App\Models\CourseRegister;
use App\Models\CourseReserve;
use App\Models\FollowUp;
use App\Models\OnlinePayment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentChangeLog;
use App\Models\PaymentMethod;
use App\Models\Secretary;
use App\Models\Student;
use App\Models\Technical;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\User\SecretaryRepository;
use App\Services\SpotPlayer\SpotPlayerService;

class Index extends Component
{
    use LivewireAlert, WithPagination;

    public $editPaidAmount;
    public $editPayDate;
    public $editPaymentMethod;
    public $editPaymentId;
    public $payment;
    public int $paymentId;
    public string $search = '';
    public string $startDate = '';
    public string $endDate = '';
    public int $selectedSecretaryId = 0;
    public int $paymentMethod = 0;
    public string $paymentStatus = '';
    public string $paymentType = '';
    public string $rejectReason = '';
    public string $rejectDescription = '';
    public null|string $queryUserId = null;
    public null|string $queryCourseRegisterId = null;
    public null|string $queryCourseReserveId = null;
    public null|string $backUrl = null;
    public array $selectedPayments = [];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['verify', 'verifySelectedPayments', 'redoVerify'];

    public function mount()
    {
        $this->queryUserId = request()->query('user_id');
        $this->queryCourseRegisterId = request()->query('course_register_id');
        $this->queryCourseReserveId = request()->query('course_reserve_id');
        $this->backUrl = request()->query('back_url');
    }

    public function render()
    {
        $paymentRepository = resolve(PaymentRepository::class);
        $payments = $paymentRepository->getListQuery(Auth::user());

        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $payments->where(function ($query) use ($search) {
                $query->orWhereHas('user', function ($query) use ($search) {
                    userSearchQuery($query, $search);
                })
                    ->orWhereHas('paymentMethod', function ($query) use ($search) {
                        $query->where('title', 'like', '%' . $search . '%');
                    })->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($this->paymentType !== '') {
            $payments->where('paymentable_type', $this->paymentType);
        }

        if ($this->paymentStatus != '') {
            $payments->where('status', $this->paymentStatus);
        }

        if ($this->queryUserId) {
            $payments->where('user_id', $this->queryUserId);
        }

        if ($this->queryCourseRegisterId) {
            $payments->where('paymentable_type', 'CourseRegister')
                ->where('paymentable_id', $this->queryCourseRegisterId);
        }

        if ($this->queryCourseReserveId) {
            $payments->where('paymentable_type', 'CourseReserve')
                ->where('paymentable_id', $this->queryCourseReserveId);
        }

        if ($this->startDate) {
            $payments->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $payments->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        if ($this->selectedSecretaryId) {
            $secretary = Secretary::find($this->selectedSecretaryId);
            $payments->where('created_by', $secretary->user->id);
        }

        if ($this->paymentMethod) {
            $payments->where('payment_method_id', $this->paymentMethod);
        }

        $payments = $payments->with(['user', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user())->orderBy('is_active', 'desc')->get();
        $paymentMethods = PaymentMethod::active()->get();
        return view('livewire.admin.payments.index', compact('payments', 'secretaries', 'paymentMethods'));
    }

    public function verifyPayment(int $paymentId)
    {
        $this->confirm(__('payments.messages.confirm_verify'), [
            'onConfirmed' => 'verify',
        ]);
        $this->paymentId = $paymentId;
    }

    public function redoVerifyPayment(int $paymentId)
    {
        $this->confirm(__('payments.messages.confirm_redo_verify'), [
            'onConfirmed' => 'redoVerify',
        ]);
        $this->paymentId = $paymentId;
    }

    public function redoVerify()
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($this->paymentId);

            if ($payment->status == StatusEnum::REJECTED) {
                $this->alert('error', __('payments.messages.payment_already_rejected'));
                return;
            }
            $oldStatus = $payment->status;
            $payment->status = StatusEnum::PENDING;
            $payment->updated_by = Auth::id();
            $payment->save();
            $payment->refresh();

            if ($payment->paymentable instanceof CourseRegister) {
                $payment->paymentable->update([
                    'is_paid' => false,
                    'paid_amount' => $payment->paymentable->paid_amount - $payment->paid_amount,
                ]);
                $description = 'برداشت از کیف پول بابت لغو تایید پرداخت دوره حضوری';
                withdrawTransaction($payment, $description);
            }

            if ($payment->paymentable instanceof Order) {
                $order = $payment->paymentable;
                $order->update([
                    'payment_status' => 'pending',
                    'pay_date' => null,
                ]);

                $this->reDoOrderItemsLicense($order);

                $description = 'برداشت از کیف پول بابت لغو تایید پرداخت دوره آنلاین';
                withdrawTransaction($payment, $description);
            }

            if ($payment->paymentable instanceof CourseReserve) {
                $courseReserve = $payment->paymentable;
                $courseReserve->update([
                    'paid_amount' => 0,
                ]);

                $description = 'برداشت از کیف پول بابت لغو تایید پرداخت رزرو دوره';
                withdrawTransaction($payment, $description);
            }

            if ($payment->paymentable instanceof Technical) {
                $payment->paymentable->paid_amount = unformatNumber($payment->paymentable->paid_amount) - $payment->paid_amount;
                $payment->paymentable->save();
            }

            DB::commit();
            PaymentChangeLog::addLog($payment, 'status', 'وضعیت پرداخت', $oldStatus, StatusEnum::PENDING, 'لغو تایید پرداخت');
            $this->alert('success', __('payments.messages.successfully_do_verified'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('payments.messages.error_in_do_verifying'));
            return;
        }
    }

    public function verify(bool $hideAlert = false)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($this->paymentId);
            if ($payment->status == StatusEnum::VERIFIED) {
                return $this->alert('error', __('payments.messages.payment_already_verified'));
            }
            if ($payment->status == StatusEnum::REJECTED) {
                return $this->alert('error', __('payments.messages.payment_already_rejected'));
            }

            $totalPaidAmount = Payment::where([
                'status' => StatusEnum::VERIFIED,
                'paymentable_id' => $payment->paymentable_id,
                'user_id' => $payment->user_id,
            ])->sum('paid_amount');

            $oldStatus = $payment->status;


            if ($payment->paymentable instanceof CourseRegister) {

                $coursePrice = $payment->paymentable->amount > 0 ? $payment->paymentable->amount : $payment->paymentable->course->price;

                if ($payment->paid_amount > ($coursePrice - $totalPaidAmount)) {
                    return $this->alert('error', __('payments.messages.max_amount'));
                }
                $remainingAmount = $coursePrice - ($totalPaidAmount + $payment->paid_amount);
                if ($remainingAmount == 0) {
                    $payment->paymentable->update([
                        'is_paid' => true,
                        'paid_amount' => $totalPaidAmount + $payment->paid_amount
                    ]);
                } else {
                    $payment->paymentable->update([
                        'paid_amount' => $totalPaidAmount + $payment->paid_amount,
                    ]);
                }

                $description = 'واریز به کیف پول بابت تایید پرداخت دوره حضوری';
                addTransaction($payment, $description);
            }

            if ($payment->paymentable instanceof Order) {
                $order = $payment->paymentable;
                $orderPrice =  $order->final_amount;
                if ($payment->paid_amount > ($orderPrice - $totalPaidAmount)) {
                    return $this->alert('error', __('payments.messages.max_amount'));
                }

                $remainingAmount = $orderPrice - ($totalPaidAmount + $payment->paid_amount);
                if ($remainingAmount == 0) {
                    $order->update([
                        'payment_status' => 'paid',
                        'pay_date' => time(),
                    ]);

                    $this->updatedOrderItemsLicense($order);
                }

                $onlinePayment = OnlinePayment::active()->where([
                    'order_id' => $order->id,
                    'paid_amount' => $payment->paid_amount,
                    'user_id' => $payment->user_id,
                    'status' => 'pending',
                    'pay_confirm' => false,
                ])->first();

                if ($onlinePayment) {
                    $onlinePayment->status = OnlinePaymentStatusEnum::PAID;
                    $onlinePayment->pay_confirm =  true;
                    $onlinePayment->save();
                }
                $user = User::find($payment->user_id);
                addClueToStudent($user);

                $description = 'واریز به کیف پول بابت تایید پرداخت دوره آنلاین';
                addTransaction($payment, $description);
            }

            if ($payment->paymentable instanceof Technical) {
                $payment->paymentable->paid_amount = unformatNumber($payment->paymentable->paid_amount) + $payment->paid_amount;
                $payment->paymentable->save();
            }

            if ($payment->paymentable instanceof CourseReserve) {
                $courseReserve = $payment->paymentable;
                $courseReserve->update([
                    'paid_amount' => $payment->paid_amount,
                ]);

                $description = 'واریز به کیف پول بابت تایید پرداخت رزرو دوره';
                addTransaction($payment, $description);
            }

            $payment->status = StatusEnum::VERIFIED;
            $payment->updated_by = Auth::id();
            $payment->save();
            $payment->refresh();

            DB::commit();
            PaymentChangeLog::addLog($payment, 'status', 'وضعیت پرداخت', $oldStatus, StatusEnum::VERIFIED, 'تایید پرداخت');
            if (!$hideAlert) {
                $this->alert('success', __('payments.messages.successfully_verified'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('payments.messages.error_in_verifying'));
            return;
        }
    }

    public function rejectPayment()
    {
        DB::beginTransaction();
        try {
            $payment = Payment::find($this->paymentId);
            if ($payment->status == StatusEnum::VERIFIED) {
                throw new Exception(__('payments.messages.payment_already_verified'));
            }
            if ($payment->status == StatusEnum::REJECTED) {
                throw new Exception(__('payments.messages.payment_already_rejected'));
            }
            if ($this->rejectReason == '') {
                throw new Exception(__('payments.messages.select_reject_reason'));
            }
            if ($this->rejectReason == __('payments.reasons.other') && $this->rejectDescription == '') {
                throw new Exception(__('payments.messages.enter_reject_description'));
            }
            $oldStatus = $payment->status;
            $payment->status = StatusEnum::REJECTED;
            $payment->reject_description = $this->rejectReason . ' - ' . $this->rejectDescription;
            $payment->updated_by = Auth::id();
            $saveResult = $payment->save();
            FollowUp::create([
                'user_id' => $payment->user_id,
                'title' => __('follow_ups.titles.reject_payment'),
                'description' => $this->rejectReason . ' - ' . $this->rejectDescription,
                'created_by' => $payment->created_by,
                'remember_time' => now()->timezone('Asia/Tehran'),
                'step' => 'step1',
            ]);
            if ($saveResult) {
                PaymentChangeLog::addLog($payment, 'status', 'وضعیت پرداخت', $oldStatus, StatusEnum::REJECTED, 'رد کردن پرداخت');
                $this->alert('success', __('payments.messages.successfully_rejected'));
            } else {
                throw new Exception(__('payments.messages.error_in_rejecting'));
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->alert('error', $e->getMessage());
        }
    }

    public function bulkVerifyPayment()
    {
        $this->confirm(__('payments.messages.confirm_verify_selected'), [
            'onConfirmed' => 'verifySelectedPayments',
        ]);
    }

    public function verifySelectedPayments()
    {
        foreach ($this->selectedPayments as $paymentId) {
            $this->paymentId = $paymentId;
            $this->verify();
        }
        $this->selectedPayments = [];
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }

    public function updatePaymentAmount()
    {
        if ($this->editPaidAmount != $this->payment->paid_amount) {
            $payment = Payment::find($this->editPaymentId);
            $paidAmount = $payment->paid_amount;
            $payment->paid_amount = unformatNumber($this->editPaidAmount);

            if ($payment->save()) {
                if ($payment->paymentable_type == Order::class) {
                    $orderPayment = OnlinePayment::where('order_id', $payment->paymentable_id)
                        ->where('user_id', $payment->user_id)
                        ->where('status', 'pending')
                        ->where('paid_amount', $paidAmount)
                        ->first();
                    if ($orderPayment) {
                        $orderPayment->paid_amount =  $payment->paid_amount;
                        $orderPayment->save();
                    }
                }

                PaymentChangeLog::addLog($payment, 'paid_amount', 'مبلغ پرداخت', $paidAmount, unformatNumber($this->editPaidAmount), 'بروزرسانی مبلغ پرداخت');
                $this->editPaidAmount =  null;
                return 'مبلغ پرداخت,';
            }
        }
    }

    public function updatePaymentMethod()
    {
        if ($this->editPaymentMethod != $this->payment->payment_method_id) {
            $payment = Payment::find($this->editPaymentId);
            $oldPaymentMethodId = $payment->payment_method_id;
            $payment->payment_method_id = $this->editPaymentMethod;
            if ($payment->save()) {
                PaymentChangeLog::addLog($payment, 'payment_method_id', 'روش پرداخت', $oldPaymentMethodId, $this->editPaymentMethod, 'بروزرسانی روش پرداخت');
                $this->editPaymentMethod =  null;
                return 'روش پرداخت,';
            }
        }
    }

    public function updatePaymentPayDate()
    {
        if ($this->editPayDate != $this->payment->pay_date) {
            $payment = Payment::find($this->editPaymentId);
            $oldPayDate = $payment->pay_date;
            $payment->pay_date = $this->editPayDate;
            if ($payment->save()) {
                PaymentChangeLog::addLog($payment, 'pay_date', 'تاریخ پرداخت', $oldPayDate, $this->editPayDate, 'بروزرسانی تاریخ پرداخت');
                $this->editPayDate =  null;
                return 'تاریخ پرداخت,';
            }
        }
    }

    public function setEditPaymentData(int $paymentId)
    {
        $this->editPaymentId = $paymentId;
        $this->payment = Payment::find($paymentId);
        $this->editPaidAmount = $this->payment->paid_amount;
        $this->editPayDate = $this->payment->pay_date;
        $this->editPaymentMethod = $this->payment->payment_method_id;
    }

    public function updatePayment()
    {
        if ($this->editPaidAmount > 0) {
            $amountText = $this->updatePaymentAmount();
        } else {
            $this->alert('error', 'لطفا مبلغ مورد نظر را وارد کنید');
        }

        if ($this->editPaymentMethod > 0) {
            $methodText = $this->updatePaymentMethod();
        } else {
            $this->alert('error', 'لطفا روش پرداخت مورد نظر را انتخاب کنید');
        }

        if ($this->editPayDate) {
            $dateText = $this->updatePaymentPayDate();
        } else {
            $this->alert('error', 'لطفا تاریخ پرداخت مورد نظر را وارد کنید');
        }

        if ($amountText || $methodText || $dateText) {

            $this->alert('success', " $amountText $methodText $dateText با موفقیت بروزرسانی شد");
        } else {
            $this->alert('error', 'تغییری صورت نگرفت');
        }
    }

    private function reDoOrderItemsLicense($order)
    {
        foreach ($order->orderItems as $orderItem) {

            try {
                $orderItem->pay_date = null;
                $orderItem->save();
            } catch (Exception $e) {
                echo ($e->getMessage());
            }
            $orderItem->onlineCourse->registered_count = $orderItem->onlineCourse->registered_count - 1;
            $orderItem->onlineCourse->save();
        }
    }

    private function updatedOrderItemsLicense($order)
    {
        foreach ($order->orderItems as $orderItem) {
            try {
                $License = SpotPlayerService::license($order->user->full_name, [$orderItem->spot_key], [$order->user->mobile], false);
                $orderItem->license_key = $License['key'];
                $orderItem->license_url = $License['url'];
                $orderItem->license_id = $License['_id'];
                $orderItem->pay_date = time();
                $orderItem->save();
            } catch (Exception $e) {
                echo ($e->getMessage());
            }
            $orderItem->onlineCourse->registered_count = $orderItem->onlineCourse->registered_count + 1;
            $orderItem->onlineCourse->save();
        }
        // ===== update discount  used count  =====
        if ($order->discount_id > 0) {
            $order->discount->used_count = $orderItem->discount->used_count + 1;
            $order->discount->save();
        }
    }

    public function setSelectedPaymentId($paymentId)
    {
        $this->selectedPayments[] = $paymentId;
    }

    public function unsetSelectedPaymentId($paymentId)
    {
        $this->selectedPayments = array_diff($this->selectedPayments, [$paymentId]);
    }
}
