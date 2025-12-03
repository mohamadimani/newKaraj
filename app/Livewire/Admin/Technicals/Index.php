<?php

namespace App\Livewire\Admin\Technicals;

use App\Enums\CourseRegister\StatusEnum as CourseRegisterStatusEnum;
use App\Enums\Technical\StatusEnum;
use App\Models\CourseRegister;
use App\Models\Payment;
use App\Models\PaymentImage;
use App\Models\PaymentMethod;
use App\Models\Secretary;
use App\Models\Technical;
use App\Repositories\Course\TechnicalRepository;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use App\Enums\Payment\StatusEnum as PaymentStatusEnum;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    //payment
    public $technicalAmount;
    public $technicalAmountDescription;
    public $technicalPayDate;
    public $technicalPaymentMethodId;
    public $technicalPaidImage;

    public $search;
    public $selectedSecretaryId;
    public $startDate;
    public $endDate;
    public $technicalId;
    public $status = null;
    use WithPagination, WithFileUploads, LivewireAlert;
    public $paginationTheme = 'bootstrap';
    protected $listeners = ['updateStatus'];
    public function render()
    {
        $technicals = resolve(TechnicalRepository::class)->getListQuery()->with('course')->where('status', StatusEnum::PROCESSING); // this code has to be duplicate to work

        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $technicals = $technicals->whereHas('user', function ($query) use ($search) {
                userSearchQuery($query, $search);
            });
            $technicals = $technicals->orWhereHas('course', function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%");
            })->where('status', StatusEnum::PROCESSING->value);
            $technicals = $technicals->orWhereHas('user.student', function ($query) use ($search) {
                $query->where('national_code', 'LIKE', "%$search%");
            });
        }

        if ($this->selectedSecretaryId) {
            $secretary = Secretary::find($this->selectedSecretaryId);
            $technicals = $technicals->where('created_by', $secretary->user_id);
        }

        if ($this->startDate) {
            $technicals->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $technicals->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        $technicals = $technicals->where('status', StatusEnum::PROCESSING->value) // this code has to be duplicate to work
            ->with('course')
            ->with('user.student')
            ->orderBy('id', 'desc')
            ->paginate(30);

        $secretaries = resolve(SecretaryRepository::class)->getListQuery(Auth::user())->orderBy('is_active', 'desc')->get();
        $paymentMethods = PaymentMethod::active()->get();

        return view('livewire.admin.technicals.index', compact('technicals', 'secretaries', 'paymentMethods'));
    }

    public function updateStatusToIntroduced($technicalId)
    {
        $this->confirm('به معرفی شده تغییر کند؟', [
            'onConfirmed' => 'updateStatus',
        ]);
        $this->technicalId = $technicalId;
        $this->status = StatusEnum::INTRODUCED->value;
    }

    public function updateStatusToCancelled($technicalId)
    {
        $this->confirm('این درخواست لغو شود؟', [
            'onConfirmed' => 'updateStatus',
        ]);
        $this->technicalId = $technicalId;
        $this->status = StatusEnum::CANCELLED->value;
    }

    public function updateStatus()
    {
        $technical = Technical::find($this->technicalId);
        $technical->status = $this->status;
        $technical->save();

        if ($this->status == StatusEnum::CANCELLED->value) {
            $courseRegister = CourseRegister::where('id', $technical->course_register_id)->first();
            $courseRegister->status = CourseRegisterStatusEnum::REGISTERED->value;
            $courseRegister->save();
        }

        // if ($this->status == StatusEnum::INTRODUCED->value) {
        //     event(new TechnicalUpdatedToIntroduced($technical));
        // }
        return $this->alert('success', __('public.messages.successfully_done'));
    }

    public function setTechnicalRegisterInfo($technicalId)
    {
        $this->technicalId = $technicalId;
    }

    public function storePayment($technicalId)
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

        $technical = Technical::find($technicalId);
        if ($this->technicalAmount > 0) {
            $payment = Payment::create([
                'paymentable_id' => $technical->id,
                'paymentable_type' => Technical::class,
                'description' => $this->technicalAmountDescription,
                'created_by' => Auth::id(),
                'branch_id' => $technical->branch_id,
                'status' => PaymentStatusEnum::PENDING,
                'user_id' => $technical->user_id,
                'paid_amount' => $this->technicalAmount,
                'payment_method_id' => $this->technicalPaymentMethodId,
                'pay_date' => $this->technicalPayDate,
            ]);

            if ($this->technicalPaidImage) {
                $imageName = Verta(now()->timestamp)->format('m') . '/' . time() . '.' . $this->technicalPaidImage->extension();
                $this->technicalPaidImage->storeAs('payments/bill/', $imageName, 'paymentImage');
                PaymentImage::create([
                    'payment_id' => $payment->id,
                    'title' => $imageName,
                    'description' => $technical->amount_descreption,
                    'create_by' => Auth::id(),
                ]);
                return $this->alert('success', __('public.messages.successfully_done'));
            }

        }
    }

}
