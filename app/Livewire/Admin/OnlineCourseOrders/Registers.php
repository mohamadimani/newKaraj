<?php

namespace App\Livewire\Admin\OnlineCourseOrders;

use App\Enums\Payment\StatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentImage;
use App\Models\PaymentMethod;
use App\Models\Secretary;
use App\Models\Technical;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use App\Enums\Technical\StatusEnum as TechnicalStatusEnum;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Registers extends Component
{
    public $search = '';
    public $selectedSecretaryId = null;
    public $startDate = null;
    public $endDate = null;
    public $orderItemId = null;
    public $showModal = true;
    public $technicalAmount;
    public $technicalPaymentMethodId;
    public $technicalPayDate;
    public $technicalPaidImage;
    public $technicalAmountDescription;

    use WithPagination, LivewireAlert, WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $orderItems = OrderItem::active()->with('user')->where('pay_date', 'IS NOT', null);
        if (mb_strlen($this->search) > 0) {
            $search = trim($this->search);
            $orderItems->where('id', 'like', '%' . $search . '%')
                ->orWhereHas('user', function ($query) use ($search) {
                    userSearchQuery($query, $search);
                });
        }
        if ($this->selectedSecretaryId) {
            $secretary = Secretary::find($this->selectedSecretaryId);
            $orderItems = $orderItems->where('created_by', $secretary->user->id);
        }
        if ($this->startDate) {
            $orderItems->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $orderItems->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        $orderItems = $orderItems->orderBy('created_at', 'desc')->where('pay_date', 'IS NOT', null)->paginate(30);

        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user())->orderBy('is_active', 'desc')->get();
        $paymentMethods = PaymentMethod::all();

        return view('livewire.admin.online-course-orders.registers', compact('orderItems', 'secretaries', 'paymentMethods'));
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }

    public function setTechnicalRegisterInfo($orderItemId)
    {
        if ($orderItem = OrderItem::find($orderItemId) and !$this->checkStudentTechnicalInfo($orderItem?->user?->student)) {
            return $this->alert('error', 'اطلاعات و مدارک کارآموز باید تکمیل باشد');
        }
        $this->orderItemId = $orderItemId;
        $this->showModal = true;
    }

    public function checkStudentTechnicalInfo($student)
    {
        if (!trim($student?->father_name)) {
            return false;
        }
        if (!trim($student?->national_code)) {
            return false;
        }
        if (!trim($student?->personal_image)) {
            return false;
        }
        if (!trim($student?->user->birth_date)) {
            return false;
        }
        return true;
    }

    public function registerTechnical($orderItemId)
    {
        $rules = [
            'technicalAmount' => 'required|numeric|min:0',
            'technicalAmountDescription' => 'required|string|max:256',
        ];

        if ($this->technicalAmount > 0) {
            $rules['technicalPayDate'] = 'required';
            $rules['technicalPaymentMethodId'] = 'required|exists:payment_methods,id';
            $rules['technicalPaidImage'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2000';
        } else {
            $rules['technicalPayDate'] = 'nullable';
            $rules['technicalPaymentMethodId'] = 'nullable|exists:payment_methods,id';
            $rules['technicalPaidImage'] = 'nullable';
        }

        $this->validate($rules);

        $orderItem = OrderItem::find($orderItemId);
        $this->orderItemId = null;
        $this->showModal = null;
        if (!$orderItem) {
            return $this->alert('error', 'دوره ای با این شناسه یافت نشد');
        }

        if ($technical = Technical::where(['course_register_id' => $orderItemId, 'user_id' => $orderItem->user_id,])->whereIn('status', [TechnicalStatusEnum::PROCESSING->value, TechnicalStatusEnum::INTRODUCED->value, TechnicalStatusEnum::DONE->value])->first()) {
            return $this->alert('error', 'فنی حرفه ای قبلا ثبت شده است');
        }
        DB::beginTransaction();
        try {
            $technical = Technical::create([
                'user_id' => $orderItem->user_id,
                'student_id' => $orderItem->user->student->id,
                'course_register_id' => $orderItemId,
                'course_id' => $orderItem->online_course_id,
                'paid_amount' => 0,
                'amount_descreption' => $this->technicalAmountDescription,
                'status' => TechnicalStatusEnum::PROCESSING,
                'created_by' => Auth::id(),
                'branch_id' => $orderItem->user->clue->branch_id,
                'is_online_course' => true,
            ]);

            if ($this->technicalAmount > 0) {
                $payment = $this->createPaymentForTechnical($technical, $this->technicalAmount, $this->technicalPaymentMethodId, $this->technicalPayDate, $this->technicalPaidImage);
            }

            $this->technicalAmount = null;
            $this->technicalAmountDescription = null;

            DB::commit();
            return $this->alert('success', 'فنی حرفه ای با موفقیت ثبت شد');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->alert('error', 'مشکلی در انجام عملیات بوجود آمده است');
        }
    }

    public function createPaymentForTechnical($technical, $paidAmount, $paymentMethodId, $payDate, $technicalPaidImage)
    {
        $payment = Payment::create([
            'paymentable_id' => $technical->id,
            'paymentable_type' => Technical::class,
            'description' => $technical->amount_descreption,
            'created_by' => Auth::id(),
            'branch_id' => $technical->branch_id,
            'status' => StatusEnum::PENDING,
            'user_id' => $technical->user_id,
            'paid_amount' => $paidAmount,
            'payment_method_id' => $paymentMethodId,
            'pay_date' => $payDate,
        ]);

        if ($technicalPaidImage) {
            $imageName = Verta(now()->timestamp)->format('m') . '/' . time() . '.' . $technicalPaidImage->extension();
            $technicalPaidImage->storeAs('payments/bill/', $imageName, 'paymentImage');
            PaymentImage::create([
                'payment_id' => $payment->id,
                'title' => $imageName,
                'description' => $technical->amount_descreption,
                'create_by' => Auth::id(),
            ]);
        }
        return $payment;
    }

    public function makeStudentAccount($userId)
    {;
        if ($user = User::find($userId)) {
            addClueToStudent($user);
            return $this->alert('success', 'با موفقیت ایجاد شد');
        }
        return $this->alert('error', 'مشکلی در انجام عملیات بوجود آمده است');
    }
}
