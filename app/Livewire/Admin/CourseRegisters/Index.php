<?php

namespace App\Livewire\Admin\CourseRegisters;

use App\Constants\PermissionTitle;
use App\Enums\CourseRegister\StatusEnum;
use App\Enums\CourseReserve\StatusEnum as CourseReserveStatusEnum;
use App\Enums\Payment\StatusEnum as PaymentStatusEnum;
use App\Enums\Technical\StatusEnum as TechnicalStatusEnum;
use App\Jobs\SendSingleSmsJob;
use App\Models\Course;
use App\Models\CourseRegister;
use App\Models\CourseRegisterChangeLog;
use App\Models\CourseReserve;
use App\Models\Payment;
use App\Models\PaymentImage;
use App\Models\PaymentMethod;
use App\Models\Refund;
use App\Models\Secretary;
use App\Models\Student;
use App\Models\Technical;
use App\Models\UserExamNumber;
use App\Repositories\Course\CourseRegisterRepository;
use App\Repositories\User\SecretaryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    public $technicalAmount;
    public $technicalAmountDescription;
    public $technicalPaymentMethodId;
    public $technicalPayDate;
    public $technicalPaidImage;

    public $search = '';
    public $selectedSecretaryId = null;
    public $startDate = null;
    public $endDate = null;
    public $idCardImage;
    public $showModalId;
    public $courseRegisterId = null;
    public $reserveCourseRegisterId = null;
    public $reserve_description = null;
    public $showModal = false;
    public $cancel_description = null;

    public $amount;
    public $amount_description;

    public $selectedStudents = [];
    public $selectAllStudents = null;
    public $smsMessage = null;
    // exam
    public $exam_number = null;
    public $exam_description = null;
    public $courseSearch = null;
    //refund
    public $refund_amount = null;
    public $refund_description = null;

    protected $listeners = ['convertRegisterToReserve', 'doCancelCourseRegister'];

    use WithPagination, LivewireAlert, WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        if ($courses = Cache::get('courseRegisterCourses')) {
        } else {
            $courses = Course::active()->orderBy('id', 'DESC')->get();
            Cache::put('courseRegisterCourses', $courses, 3600);
        }

        if ($paymentMethods = Cache::get('courseRegisterPaymentMethod')) {
        } else {
            $paymentMethods = PaymentMethod::active()->get();
            Cache::put('courseRegisterPaymentMethod', $paymentMethods, 3600);
        }

        $courseRegisters = resolve(CourseRegisterRepository::class)->getListQuery(Auth::user())->where('status', StatusEnum::REGISTERED);
        if (mb_strlen($this->search) > 2) {
            $search = trim($this->search);
            $courseRegisters->where(function ($query) use ($search) {
                $query->whereHas('course', function ($q) use ($search) {
                    $q->whereLike('title', "%$search%");
                })
                    ->orWhereHas('student', function ($studentQ) use ($search) {
                        $studentQ->whereHas('user', function ($userQ) use ($search) {
                            userSearchQuery($userQ, $search);
                        });
                        $studentQ = $studentQ->orWhere('national_code', 'LIKE', "%$search%");
                    });
            });
        }
        if (mb_strlen($this->courseSearch) > 2) {
            $courseSearch = trim($this->courseSearch);
            $courseRegisters->where(function ($query) use ($courseSearch) {
                $query->whereHas('course', function ($q) use ($courseSearch) {
                    $q->whereLike('title', "%$courseSearch%");
                });
            });
        }

        if ($this->selectedSecretaryId) {
            $courseRegisters->where('secretary_id', $this->selectedSecretaryId);
        }
        if ($this->startDate) {
            $courseRegisters->where('created_at', '>=', date('Y-m-d', jalaliToTimestamp($this->startDate)));
        }
        if ($this->endDate) {
            $courseRegisters->where('created_at', '<=', date('Y-m-d', jalaliToTimestamp($this->endDate) + 86400));
        }

        if ($this->selectAllStudents == 'yes') {
            $this->selectedStudents = $courseRegisters->pluck('student_id')->toArray();
        }
        if ($this->selectAllStudents == 'no') {
            $this->selectedStudents = [];
        }

        $courseRegisters = $courseRegisters
            ->with(['student.user.clue', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        if ($courseRegisterLogs = Cache::get('courseRegisterLogs')) {
        } else {
            $courseRegisterLogs = CourseRegisterChangeLog::query()->with('user')->orderBy('created_at', 'desc')->limit(100)->get();
            Cache::put('courseRegisterLogs', $courseRegisterLogs, 60);
        }

        $secretaries = Secretary::get();
        return view('livewire.admin.course-registers.index', compact('courseRegisters', 'courses', 'paymentMethods', 'courseRegisterLogs', 'secretaries'));
    }

    public function convertToReserve($courseRegisterId)
    {
        $this->confirm(__('course_registers.messages.confirm_convert_to_reserve'), [
            'onConfirmed' => 'convertRegisterToReserve',
        ]);
        $this->courseRegisterId = $courseRegisterId;
        $this->showModal = null;
    }

    public function convertRegisterToReserve()
    {
        DB::beginTransaction();
        try {
            $courseRegister = CourseRegister::find($this->reserveCourseRegisterId);
            if (in_array($courseRegister->status->value, ['cancelled', 'reserved'])) {
                $this->alert('error', __('این دوره قبلا انصراف یا رزور شده است'));
                return false;
            }
            $params = [
                'course_register_id' => $courseRegister->id,
                'clue_id' => $courseRegister?->student?->user?->clue?->id,
                'profession_id' => $courseRegister->course->profession_id,
                'secretary_id' => $courseRegister->secretary_id,
                'paid_amount' => $courseRegister->paid_amount ?? 0,
                'description' => $this->reserve_description,
                'status' => CourseReserveStatusEnum::PENDING,
                'created_by' => Auth::id(),
            ];
            $courseReserve = CourseReserve::create($params);
            foreach ($courseRegister->payments as $payment) {
                $payment->duplicate($courseReserve->id, CourseReserve::class);
            }

            $previousValue = $courseRegister->status;
            $newValue = StatusEnum::RESERVED->value;

            $courseRegister->status = StatusEnum::RESERVED->value;
            $courseRegister->save();
            $courseRegister->payments()->delete();
            CourseRegisterChangeLog::addLog($courseRegister, 'status', $previousValue, $newValue, $this->reserve_description);
            DB::commit();

            $this->alert('success', __('course_registers.messages.successfully_converted_to_reserve'));
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->alert('error', __('course_registers.messages.failed_to_convert_to_reserve'));
        }
    }

    public function cancelCourseRegister($courseRegisterId)
    {
        $this->confirm('آیا از ثبت انصراف مطمئن هستید؟', [
            'onConfirmed' => 'doCancelCourseRegister',
        ]);
        $this->courseRegisterId = $courseRegisterId;
    }

    public function doCancelCourseRegister()
    {
        DB::beginTransaction();
        try {

            $courseRegister = CourseRegister::find($this->courseRegisterId);
            // set valvue for log
            $previousValue = $courseRegister->status;
            $newValue = StatusEnum::CANCELLED->value;

            $courseRegister->status = StatusEnum::CANCELLED->value;
            $courseRegister->cancel_description = $this->cancel_description;
            $courseRegister->save();

            CourseRegisterChangeLog::addLog($courseRegister, 'status', $previousValue, $newValue, $this->cancel_description);

            $this->alert('success', 'انصراف با موفقیت انجام شد');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->alert('error', 'مشکلی در انجام عملیات بوجود آمده است');
        }
    }

    public function setCourseRegisterId($courseRegisterId)
    {
        $this->courseRegisterId = $courseRegisterId;
    }

    public function setReserveCourseRegisterId($reserveCourseRegisterId)
    {
        $this->reserveCourseRegisterId = $reserveCourseRegisterId;
    }

    public function changeCourseRegisterAmount()
    {
        if (!Auth::user()->hasPermissionTo(PermissionTitle::COURSE_REGISTER_CREATE_PRICE_CAN_CHANGE)) {
            return $this->alert('error', 'شما دسترسی تغییر مبلغ دوره را ندارید');
        }

        $this->amount = str_replace(',', '', $this->amount);
        $this->amount = (int)$this->amount;

        $this->validate([
            'amount' => 'required|numeric|min:0',
            'amount_description' => 'required|string|max:256'
        ], [
            'amount.required' => 'مبلغ الزامی است',
            'amount.numeric' => 'مبلغ باید عدد باشد',
            'amount.min' => 'مبلغ نمی تواند منفی باشد',
            'amount_description.required' => 'توضیحات مبلغ الزامی است',
            'amount_description.string' => 'توضیحات مبلغ باید متن باشد',
            'amount_description.max' => 'توضیحات مبلغ نمی تواند بیشتر از 1000 کاراکتر باشد'
        ]);


        DB::beginTransaction();
        try {
            $courseRegister = CourseRegister::find($this->courseRegisterId);
            // set log value
            $previousValue = $courseRegister->amount;
            $newValue = $this->amount;

            $courseRegister->amount = $this->amount;
            $courseRegister->amount_description = $this->amount_description;
            $courseRegister->save();

            CourseRegisterChangeLog::addLog($courseRegister, 'amount', $previousValue, $newValue, $this->amount_description);

            $this->alert('success', 'مبلغ با موفقیت تغییر کرد');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->alert('error', 'مشکلی در انجام عملیات بوجود آمده است');
        }
    }

    public function setSelectedSecretaryId($secretaryId)
    {
        $this->selectedSecretaryId = $secretaryId;
    }

    public function setTechnicalRegisterInfo($courseRegisterId)
    {
        if ($courseRegister = CourseRegister::find($courseRegisterId) and !$this->checkStudentTechnicalInfo($courseRegister->student)) {
            return $this->alert('error', 'اطلاعات و مدارک کارآموز باید تکمیل باشد');
        }
        $this->courseRegisterId = $courseRegisterId;
        $this->showModal = true;
    }

    public function checkStudentTechnicalInfo($student)
    {
        if (!trim($student->father_name)) {
            return false;
        }
        if (!trim($student->national_code)) {
            return false;
        }
        if (!trim($student->personal_image)) {
            return false;
        }
        if (!trim($student->user->birth_date)) {
            return false;
        }
        return true;
    }

    public function registerTechnical($courseRegisterId)
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

        $courseRegister = CourseRegister::find($courseRegisterId);
        $this->courseRegisterId = null;
        $this->showModal = null;
        if (!$courseRegister) {
            return $this->alert('error', 'دوره ای با این شناسه یافت نشد');
        }
        if ($technical = Technical::where(['course_register_id' => $courseRegisterId, 'user_id' => $courseRegister->student->user_id,])->whereIn('status', [TechnicalStatusEnum::PROCESSING->value, TechnicalStatusEnum::INTRODUCED->value, TechnicalStatusEnum::DONE->value])->first()) {
            return $this->alert('error', 'فنی حرفه ای قبلا ثبت شده است');
        }
        DB::beginTransaction();
        try {
            $technical = Technical::create([
                'user_id' => $courseRegister->student->user_id,
                'student_id' => $courseRegister->student_id,
                'course_register_id' => $courseRegisterId,
                'course_id' => $courseRegister->course_id,
                'paid_amount' => 0,
                'amount_descreption' => $this->technicalAmountDescription,
                'status' => TechnicalStatusEnum::PROCESSING,
                'created_by' => Auth::id(),
                'branch_id' => $courseRegister->course->branch_id,
            ]);
            if ($this->technicalAmount > 0) {
                $payment = $this->createPaymentForTechnical($technical, $this->technicalAmount, $this->technicalPaymentMethodId, $this->technicalPayDate, $this->technicalPaidImage);
            }

            $courseRegister->status = StatusEnum::TECHNICAL->value;
            $courseRegister->save();

            $this->technicalAmount = null;
            $this->technicalAmountDescription = null;

            $user = $courseRegister->student->user;
            $name = $courseRegister->student->user->full_name;
            $text = "$name عزیز درخواست فنی حرفه ای برای شما ثبت شد" . "\n آموزشگاه دنیز";

            // sendMessage($user, $text, 'kavehnegar');

            DB::commit();
            return $this->alert('success', 'فنی حرفه ای با موفقیت ثبت شد');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->alert('error', 'مشکلی در انجام عملیات بوجود آمده است');
        }
    }

    public function setPracticalExamNumber()
    {
        $this->validate([
            'exam_number' => 'required|numeric|min:0',
            'exam_description' => 'required|string|max:1000',
        ]);

        if (!$courseRegister = CourseRegister::find($this->courseRegisterId)) {
            return $this->alert('error', 'دوره ای یافت نشد');
        }
        $userId = $courseRegister->student->user_id;


        DB::beginTransaction();
        try {
            if ($userExamNumber = UserExamNumber::where([
                'user_id' => $userId,
                'student_id' => $courseRegister->student_id,
                'course_register_id' => $courseRegister->id,
                'course_id' => $courseRegister->course_id,
                'exam_type' =>  'practical',
            ])->first()) {
                $userExamNumber->update([
                    'exam_number' => $this->exam_number,
                    'description' => $this->exam_description,
                    'created_by' => Auth::id(),
                ]);
            } else {
                $userExamNumber = UserExamNumber::create([
                    'user_id' => $userId,
                    'student_id' => $courseRegister->student_id,
                    'course_register_id' => $courseRegister->id,
                    'course_id' => $courseRegister->course_id,
                    'profession_id' => $courseRegister->course->profession_id,
                    'exam_type' => 'practical',
                    'exam_number' => $this->exam_number,
                    'description' => $this->exam_description,
                    'created_by' => Auth::id(),
                ]);
            }

            $this->exam_number = null;
            $this->exam_description = null;
            $this->courseRegisterId = null;

            DB::commit();
            return $this->alert('success', 'نمره عملی با موفقیت ثبت شد');
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
            'status' => PaymentStatusEnum::PENDING,
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

    public function setSelectedStudentId($studentId)
    {
        $this->selectAllStudents = null;
        $this->selectedStudents[] = $studentId;
    }

    public function unsetSelectedStudentId($studentId)
    {
        $this->selectAllStudents = null;
        $this->selectedStudents = array_diff($this->selectedStudents, [$studentId]);
    }

    public function sendSms()
    {
        if (mb_strlen($this->smsMessage) < 1) {
            return $this->alert('error', 'متن پیام نمیتواند خالی باشد');
        }
        if (count($this->selectedStudents) == 0) {
            return $this->alert('error', 'حداقل یک شماره برای ارسال انتخاب کنید');
        }
        foreach ($this->selectedStudents as $studentId) {
            $student = Student::find($studentId);
            dispatch(new SendSingleSmsJob(
                $student->user,
                $this->smsMessage
            ));
        }
        $this->smsMessage = '';
        $this->selectedStudents = [];
        $this->selectAllStudents = null;
        return $this->alert('success', __('messages.group_sms_sent'));
    }

    public function setRefundAmount()
    {
        $this->validate([
            'refund_amount' => 'required|numeric|min:1',
            'refund_description' => 'required|string|max:1000',
        ]);

        if (!$courseRegister = CourseRegister::find($this->courseRegisterId)) {
            return $this->alert('error', 'دوره ای یافت نشد');
        }
        $userId = $courseRegister->student->user_id;
        $courseId = $courseRegister->course_id;


        DB::beginTransaction();
        try {

            $refund = Refund::create([
                'amount' => $this->refund_amount,
                'user_id' => $userId,
                'course_id' => $courseId,
                'course_register_id' => $courseRegister->id,
                'description' => $this->refund_description,
                'created_by' => Auth::id(),
            ]);


            $this->refund_amount = null;
            $this->refund_description = null;
            $this->courseRegisterId = null;

            DB::commit();
            return $this->alert('success', 'با موفقیت ثبت شد');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->alert('error', 'مشکلی در انجام عملیات بوجود آمده است');
        }
    }
}
