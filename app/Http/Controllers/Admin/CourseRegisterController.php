<?php

namespace App\Http\Controllers\Admin;

use App\Constants\PermissionTitle;
use App\Enums\CourseRegister\StatusEnum;
use App\Enums\Payment\StatusEnum as PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRegisterStoreRequest;
use App\Models\Clue;
use App\Models\Course;
use App\Models\CourseRegister;
use App\Models\CourseRegisterChangeLog;
use App\Models\Payment;
use App\Models\PaymentImage;
use App\Models\PaymentMethod;
use App\Models\PhoneInternal;
use App\Models\Secretary;
use App\Models\Student;
use App\Repositories\Course\CourseRepository;
use App\Repositories\User\ClueRepository;
use Exception;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CourseRegisterController extends Controller
{
    public function index()
    {
        Gate::authorize('index', CourseRegister::class);
        return view('admin.course-registers.index');
    }

    public function create()
    {
        Gate::authorize('create', CourseRegister::class);

        $phoneInternals = PhoneInternal::active();
        if (Auth::user()?->secretary?->id) {
            $phoneInternals = $phoneInternals->where('secretary_id', Auth::user()->secretary->id);
        }
        $phoneInternals =  $phoneInternals->get();

        $courses = resolve(CourseRepository::class)->getListQuery(Auth::user())->active()->where('end_date', '>=', now()->format('Y-m-d'))->get();


        $clue = null;
        $clues = [];
        $clueCourseRegisters = [];
        if (request()->has('clue_id')) {
            $clue = Clue::find(request()->clue_id);
            if (mb_strlen($clue->user->first_name) == 0 and mb_strlen($clue->user->last_name) == 0) {
                return redirect()->back()->with('error', 'قبل از ثبت دوره، اطلاعات سرنخ باید تکمیل شود');
            }
            $student = Student::where('user_id', $clue->user_id)->first();
            if ($student) {
                $clueCourseRegisters = CourseRegister::where('student_id', $student->id)->with('course')->get();
            }
        } else {
            $clues = resolve(ClueRepository::class)->getListQuery(Auth::user())
                ->where(function ($query) {
                    $query->whereNull('student_id')
                        ->orWhereHas('professions', function ($query) {
                            $query->whereNull('course_register_id');
                        });
                });
            $clues = $clues->with('user')->orderBy('id', 'DESC')->get();
        }

        $paymentMethods = PaymentMethod::all();

        return view('admin.course-registers.create', compact('clues', 'courses', 'phoneInternals', 'paymentMethods', 'clueCourseRegisters', 'clue'));
    }

    public function store(CourseRegisterStoreRequest $request)
    {
        Gate::authorize('store', CourseRegister::class);
        DB::beginTransaction();
        try {
            $clue = Clue::find($request->clue_id);
            $user = $clue->user;
            $course = Course::find($request->course_id);
            $student = Student::where('user_id', $clue->user_id)->first();

            $courseRegisterCount = CourseRegister::where([
                'course_id' => $request->course_id,
                'status' => StatusEnum::REGISTERED,
                'is_active' => true,
            ])->count();

            if ($course->capacity <= $courseRegisterCount) {
                return redirect()->back()->with('error', 'ظرفیت این دوره پر شده است!')->withInput();
            }

            if (in_array($request->payment_method_id, [16]) and $request->register_paid_amount > $user->wallet) {
                return redirect()->back()->with('error', 'مبلغ پرداختی بیشتر از موجودی کیف پول می باشد!')->withInput();
            }

            $isFirstRegister = false;
            //check user has same course or not
            if ($student) {
                $courseRegister = CourseRegister::where([
                    'student_id' => $student->id,
                    'course_id' => $request->course_id,
                ])->first();
                if ($courseRegister) {
                    return redirect()->back()->with('error', 'قبلا این دوره ثبت شده است')->withInput();
                }
            }
            if (!$student) {
                $student = Student::create([
                    'user_id' => $clue->user_id,
                    'created_by' => Auth::id(),
                ]);
                $isFirstRegister = true;
            }

            $clue->update([
                'student_id' => $student->id,
            ]);

            $secretary = Secretary::where('user_id', Auth::id())->first() ?? $clue->secretary;
            $phoneInternal = PhoneInternal::where('id', $request->phone_internal_id)->with('phone')->first();

            $amount = 0;
            if (Auth::user()->hasPermissionTo(PermissionTitle::COURSE_REGISTER_CREATE_PRICE_CAN_CHANGE)) {
                $amount = $request->amount > 0 ? $request->amount : $course->price;
            }
            $paymentDescription = null;
            if (in_array($request->payment_method_id, [14, 15])) {
                $paymentDescription = ' - پرداخت کمیته یا بهزیستی - ';
            }
            $paidAmountFromWallet = 0;
            if (in_array($request->payment_method_id, [16])) {
                $paymentDescription = ' - پرداخت با کیف پول- ';
                $paidAmountFromWallet = $request->register_paid_amount;
            }
            $courseRegister = CourseRegister::create([
                'student_id' => $student->id,
                'course_id' => $request->course_id,
                'internal_branch_id' => $phoneInternal->phone->branch_id,
                'secretary_id' => $secretary->id ?? Auth::id(),
                'description' => $request->description . $paymentDescription,
                'created_by' => Auth::id(),
                'is_first_register' => $isFirstRegister,
                'amount' => $amount,
                'paid_amount' => $paidAmountFromWallet,
            ]);

            $clue->professions()->wherePivot('profession_id', $courseRegister->course->profession_id)->update([
                'course_register_id' => $courseRegister->id,
            ]);

            if (!Auth::user()->hasPermissionTo(PermissionTitle::COURSE_REGISTER_WITHOUT_PAYMENT) or $request->register_paid_amount > 0 or !in_array($request->payment_method_id, [14, 15])) {
                if ($request->payment_method_id == 16) {
                    $payment = Payment::create([
                        'paid_amount' => $request->register_paid_amount,
                        'paymentable_id' => $courseRegister->id,
                        'paymentable_type' => CourseRegister::class,
                        'payment_method_id' => $request->payment_method_id,
                        'branch_id' => $courseRegister->course->branch_id,
                        'pay_date' => $request->pay_date ?? Verta(now()->timestamp)->format('Y/m/d H:i:s'),
                        'description' => $request->payment_description . $paymentDescription,
                        'user_id' => $clue->user_id,
                        'created_by' => Auth::id(),
                        'is_wallet_pay' =>  true,
                        'status' => PaymentStatusEnum::VERIFIED
                    ]);

                    $description = 'برداشت از کیف پول بابت پرداخت از کیف پول برای دوره حضوری';
                    withdrawTransaction($payment, $description, 1);
                } else {
                    $payment = Payment::create([
                        'paid_amount' => $request->register_paid_amount,
                        'paymentable_id' => $courseRegister->id,
                        'paymentable_type' => CourseRegister::class,
                        'payment_method_id' => $request->payment_method_id,
                        'branch_id' => $courseRegister->course->branch_id,
                        'pay_date' => $request->pay_date ?? Verta(now()->timestamp)->format('Y/m/d H:i:s'),
                        'description' => $request->payment_description,
                        'user_id' => $clue->user_id,
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

            if ($request->has('redirect_back')) {
                return redirect()->back()->with('success', __('course_registers.successfully_created'));
            }

            return redirect()->route('course-registers.index')->with('success', __('course_registers.successfully_created'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(CourseRegister $courseRegister)
    {
        $courses = resolve(CourseRepository::class)->getListQuery(Auth::user())
            ->orderBy('start_date', 'asc')->get();
        return view('admin.course-registers.edit', compact('courseRegister', 'courses'));
    }

    public function update(CourseRegister $courseRegister, Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        if ($courseRegister->course_id == $request->course_id) {
            return redirect()->back()->withErrors(['course_id' => 'دوره جدید نمی‌تواند با دوره فعلی یکسان باشد.']);
        }

        DB::beginTransaction();
        try {
            $previousValue = $courseRegister->course_id;
            $newValue = $request->course_id;

            $courseRegister->update([
                'course_id' => $request->course_id,
            ]);
            CourseRegisterChangeLog::addLog($courseRegister, 'course_id', $previousValue, $newValue, $request->description);
            DB::commit();
            return redirect()->route('course-registers.index')->with('success', 'تغییر دوره با موفقیت انجام شد');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
