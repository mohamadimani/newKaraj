<?php

namespace App\Livewire\Admin\CourseStudents;

use App\Constants\PermissionTitle;
use App\Jobs\SendSingleSmsJob;
use App\Models\CourseRegister;
use App\Models\CourseRegisterChangeLog;
use App\Models\PaymentMethod;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $course;
    public $courseRegisterId;
    public $smsMessage;
    public $selectedStudents = [];
    public $selectAllStudents = null;
    public $amount;
    public $amount_description;

    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $courseRegisters = $this->course->courseRegisters()->with('student')->get();

        if ($this->selectAllStudents == 'yes') {
            $this->selectedStudents = $courseRegisters->pluck('student_id')->toArray();
        }
        if ($this->selectAllStudents == 'no') {
            $this->selectedStudents = [];
        }

        $paymentMethods = PaymentMethod::all();
        return view('livewire.admin.course-students.index', compact('courseRegisters', 'paymentMethods'));
    }

    public function setGetPack($courseRegisterId)
    {
        $courseRegister = CourseRegister::find($courseRegisterId);
        $res = $courseRegister->update([
            'get_pack' => true
        ]);
        if ($res) {
            return $this->alert('success', 'با موفقیت ثبت شد');
        }
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

    public function   withdrawCourse()
    {
        if ($this->course->teacher_withdraw_at == null) {
            $this->course->teacher_withdraw_at = time();
            $this->course->teacher_withdraw_by = user()->id;
            $this->course->save();
        }
        return $this->alert('success', 'با موفقیت ثبت شد');
    }

    public function  reDoWithdrawCourse()
    {
        $this->course->teacher_withdraw_at = null;
        $this->course->teacher_withdraw_by = null;
        $this->course->save();

        return $this->alert('success', 'با موفقیت ثبت شد');
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

    public function setCourseRegisterId($courseRegisterId)
    {
        $this->courseRegisterId = $courseRegisterId;
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
}
