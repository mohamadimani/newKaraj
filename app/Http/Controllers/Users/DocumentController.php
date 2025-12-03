<?php

namespace App\Http\Controllers\Users;

use App\Enums\Payment\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\CourseRegister;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Survey;
use App\Models\UserExamNumber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{

    public function courseLicense()
    {
        $userCourseRegisters = Auth::user()?->student?->courseRegisters ?? [];
        $orderItems = Auth::user()->orderItems;
        return view('users.documents.course-license', compact('userCourseRegisters', 'orderItems'));
    }

    public function courseLicenseShow(CourseRegister $courseRegister)
    {
        if (!in_array($courseRegister->id, user()->student->courseRegisters->pluck('id')->toArray())) {
            return Redirect()->back();
        }
        // if (!$this->hasCourseCompletedPayment($courseRegister)) {
        //     return Redirect()->route('user.documents.course-license')->with('error', 'این دوره تسویه نشده است!');
        // }
        if ($courseRegister->course->end_date >= now()) {
            return Redirect()->route('user.documents.course-license')->with('error', 'این دوره هنوز تمام نشده است!');
        }
        if (mb_strlen(trim(user()->student->personal_image)) == 0) {
            return Redirect()->route('user.documents.course-license')->with('error', 'لطفا مدارک خود را بارگذاری کنید!');
        }

        // $userScore = UserExamNumber::where([
        //     'user_id' => user()->id,
        //     'student_id' => $courseRegister->student_id,
        //     'course_register_id' => $courseRegister->id,
        //     'course_id' => $courseRegister->course_id,
        //     'profession_id' => $courseRegister->course->profession_id,
        // ])->pluck('exam_number')->sum();


        if (Survey::where(['user_id' => user()->id, 'course_register_id' => $courseRegister->id])->first()) {
            return view('users.documents.course-license-show', compact('courseRegister'));
        } else {
            return view('users.survey.index', compact('courseRegister'));
        }
    }

    public function onlineCourseLicenseShow(OrderItem $orderItem)
    {
        if (!in_array($orderItem->id, user()->orderItems->pluck('id')->toArray())) {
            return Redirect()->back();
        }
        if (!$orderItem->pay_date) {
            return Redirect()->route('user.documents.course-license')->with('error', 'این دوره تسویه نشده است!');
        }

        if (mb_strlen(trim(user()->student->personal_image)) == 0) {
            return Redirect()->route('user.documents.course-license')->with('error', 'لطفا مدارک خود را بارگذاری کنید!');
        }
        return view('users.documents.online-course-license-show', compact('orderItem'));
    }

    public function hasCourseCompletedPayment($courseRegister)
    {
        $totalPaidAmount = Payment::where([
            'status' => StatusEnum::VERIFIED,
            'paymentable_id' => $courseRegister->id,
            'paymentable_type' => courseRegister::class,
            'user_id' => user()->id,
        ])->sum('paid_amount');
        if ($totalPaidAmount == $courseRegister->amount or $totalPaidAmount == $courseRegister->course->price) {
            return true;
        }
        return false;
    }

    public function identityUpload()
    {
        return view('users.documents.identity-upload');
    }

    public function identityStore(Request $request)
    {
        $request->validate([
            'personal_image' => user()?->student?->personal_image ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:4400' : 'required|image|mimes:jpeg,png,jpg,gif|max:4400',
            'id_card_image' => (user()?->student?->id_card_image ? '' : 'required_without:birth_certificate_image') . '|nullable|image|mimes:jpeg,png,jpg,gif|max:4400',
            'birth_certificate_image' => (user()?->student?->birth_certificate_image ? '' : 'required_without:id_card_image') . '|nullable|image|mimes:jpeg,png,jpg,gif|max:4400',
            'national_code' => 'required|digits:10|unique:students,national_code,' . user()?->student?->id,
            'father_name' => 'required|string|max:255',
            'birth_date' => 'required',
        ]);

        if ($request->national_code and !checkNationalCode($request->national_code)) {
            return redirect()->back()->with('error', 'کد ملی صحیح نیست');
        }

        DB::beginTransaction();
        try {
            $studentUpdateRes = user()?->student?->update([
                'father_name' => $request->father_name ?? null,
                'national_code' => $request->national_code ?? null,
            ]);

            $userUpdateRes = user()->update([
                'birth_date' => $request->birth_date ? toGeorgianDate($request->birth_date) : null,
            ]);
            if ($studentUpdateRes and $userUpdateRes) {
                $alertType = 'success';
                $alertMessage = 'اطلاعات با موفقیت ثبت شد';
                DB::commit();
            }
            if ($request->personal_image) {
                if (user()->student->personal_image) {
                    DeleteImage('students/personal/' . user()->student->personal_image);
                }
                user()->student->personal_image = SaveImage($request->personal_image, 'students/personal');
            }
            if ($request->id_card_image) {
                if (user()->student->id_card_image) {
                    DeleteImage('students/id-card/' . user()->student->id_card_image);
                }
                user()->student->id_card_image = SaveImage($request->id_card_image, 'students/id-card');
            }
            if ($request->birth_certificate_image) {
                if (user()->student->birth_certificate_image) {
                    DeleteImage('students/birth-certificate/' . user()->student->birth_certificate_image);
                }
                user()->student->birth_certificate_image = SaveImage($request->birth_certificate_image, 'students/birth-certificate');
            }
            if ($request->personal_image or $request->birth_certificate_image or $request->id_card_image) {
                user()->student->save();
                DB::commit();
                return redirect()->route('user.documents.identity-upload', user()->student->id)->with('success', __('students.messages.successfully_updated'));
            }
            return redirect()->back()->with($alertType, $alertMessage);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function storeSurvey(Request $request, CourseRegister $courseRegister)
    {
        if ($survey = Survey::where(['user_id' => user()->id, 'course_register_id' => $courseRegister->id])->first()) {
            return redirect()->route('user.documents.course-license-show', [$courseRegister->id]);
        }
        $survey = Survey::create([
            'user_id' => user()->id,
            'course_register_id' => $courseRegister->id,
            'comment' => $request->comment ?? null,
            'star' => $request->star ?? null,
            'q_1' => $request->q_1 ?? null,
            'q_1_comment' => $request->q_1_comment ?? null,
            'q_2' => $request->q_2 ?? null,
            'q_2_comment' => $request->q_2_comment ?? null,
            'q_3' => $request->q_3 ?? null,
            'q_3_comment' => $request->q_3_comment ?? null,
            'q_4' => $request->q_4 ?? null,
            'q_4_comment' => $request->q_4_comment ?? null,
            'yes_no_q_1' => $request->yes_no_q_1 ?? null,
            'yes_no_q_2' => $request->yes_no_q_2 ?? null,
            'yes_no_q_3' => $request->yes_no_q_3 ?? null,
        ]);
        if ($survey) {
            return redirect()->route('user.documents.course-license-show', [$courseRegister->id])->withMessage('با موفقیت ثبت شد');
        }
        return redirect()->route('user.documents.course-license-show', [$courseRegister->id])->withMessage('مشکل در ثبت');
    }
}
