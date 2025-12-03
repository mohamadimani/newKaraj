<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CourseReserve\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConvertReserveToCourseRequest;
use App\Http\Requests\Admin\CourseReserveStoreRequest;
use App\Models\Clue;
use App\Models\CourseRegister;
use App\Models\CourseReserve;
use App\Models\FamiliarityWay;
use App\Models\Payment;
use App\Models\PaymentImage;
use App\Models\PaymentMethod;
use App\Models\PhoneInternal;
use App\Models\Secretary;
use App\Models\Student;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Profession\ProfessionRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CourseReserveController extends Controller
{
    public function index()
    {
        Gate::authorize('index', CourseReserve::class);
        return view('admin.course-reserves.index');
    }

    public function create()
    {
        Gate::authorize('create', CourseReserve::class);
        $clues = Clue::query()
            ->orderBy('created_at', 'desc')
            ->get();

        $professions = resolve(ProfessionRepository::class)->getListQuery(Auth::user())
            ->orderBy('created_at', 'desc')
            ->get();

        $secretaries = Secretary::query()
            ->orderBy('created_at', 'desc')
            ->get();

        $paymentMethods = PaymentMethod::query()
            ->orderBy('created_at', 'desc')
            ->get();

        $clueCourseReserves = [];
        if (request()->has('clue_id')) {
            $clueCourseReserves = CourseReserve::where('clue_id', request()->clue_id)->with('profession')->get();
        }

        return view('admin.course-reserves.create', compact('clues', 'professions', 'secretaries', 'paymentMethods', 'clueCourseReserves'));
    }

    public function store(CourseReserveStoreRequest $request)
    {
        Gate::authorize('store', CourseReserve::class);
        DB::beginTransaction();
        try {
            $courseReserve = CourseReserve::create([
                'clue_id' => $request->clue_id,
                'profession_id' => $request->profession_id,
                'secretary_id' => $request->secretary_id,
                'course_reserve_description' => $request->course_reserve_description,
                'status' => StatusEnum::PENDING,
                'created_by' => Auth::id(),
            ]);
            $clue = Clue::find($request->clue_id);
            $payment = Payment::create([
                'payment_method_id' => $request->payment_method_id,
                'paid_amount' => $request->paid_amount,
                'description' => $request->payment_description,
                'pay_date' => $request->pay_date,
                'paymentable_type' => CourseReserve::class,
                'paymentable_id' => $courseReserve->id,
                'branch_id' => $clue->branch_id,
                'user_id' => $clue->user_id,
                'created_by' => Auth::id(),
            ]);

            if ($request->paid_image) {
                $imageName = Verta(now()->timestamp)->format('m') . '/' . SaveImage($request->paid_image, 'payments/bill/' . Verta(now()->timestamp)->format('m') . '/');
                PaymentImage::create([
                    'payment_id' => $payment->id,
                    'title' => $imageName,
                    'description' => $request->payment_description,
                    'create_by' => Auth::id(),
                ]);
            }
            DB::commit();

            return redirect()->route('course-reserves.index')->with('success', __('course_reserves.messages.successfully_created'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function convertToCourseView(CourseReserve $courseReserve)
    {
        Gate::authorize('convertToCourseView', $courseReserve);
        $clues = Clue::query()
            ->orderBy('created_at', 'desc')
            ->get();

        $phoneInternals = PhoneInternal::query();

        if (Auth::user()->isSecretary()) {
            $phoneInternals->where('secretary_id', Auth::user()->secretary->id);
        }

        $phoneInternals = $phoneInternals->orderBy('created_at', 'desc')->get();

        $courses = resolve(CourseRepository::class)->getListQuery(Auth::user())
            ->orderBy('created_at', 'desc')
            ->get();

        $paymentMethods = PaymentMethod::query()
            ->orderBy('created_at', 'desc')
            ->get();

        $clueCourseReserves = CourseReserve::where('clue_id', $courseReserve->clue_id)->with('profession')->get();

        return view('admin.course-reserves.convert-to-course', compact('courseReserve', 'clues', 'phoneInternals', 'courses', 'paymentMethods', 'clueCourseReserves'));
    }

    public function convertToCourse(ConvertReserveToCourseRequest $request)
    {
        Gate::authorize('convertToCourse', CourseReserve::class);
        DB::beginTransaction();
        try {
            $courseReserve = CourseReserve::where('id', $request->course_reserve_id)->with('clue')->first();
            $studentUserId = $courseReserve->clue->user_id;

            $student = Student::firstOrCreate([
                'user_id' => $studentUserId,
            ], [
                'created_by' => Auth::id(),
            ]);

            $courseRegister = CourseRegister::create([
                'student_id' => $student->id,
                'course_id' => $request->course_id,
                'internal_branch_id' => $courseReserve->clue->branch_id,
                'secretary_id' => $courseReserve->secretary_id,
                'paid_amount' => $courseReserve->paid_amount,
                'description' => $request->description,
                'user_id' => $studentUserId,
                'created_by' => Auth::id(),
            ]);
            $courseRegister->created_at = $courseReserve->created_at;
            $courseRegister->save();

            foreach ($courseReserve->payments as $payment) {
                if (is_null($payment->discount_id)) {
                    $newPayment = $payment->duplicate($courseRegister->id, CourseRegister::class);
                }
            }

            $courseReserve->payments()->delete();

            //   $newPayment =Payment::create([
            //       'payment_method_id' => $payment->payment_method_id,
            //       'paid_amount' => $payment->paid_amount,
            //       'description' => $payment->description,
            //       'pay_date' => $payment->pay_date,
            //       'paymentable_type' => CourseRegister::class,
            //       'paymentable_id' => $courseRegister->id,
            //       'branch_id' => $courseRegister->course->branch_id,
            //       'user_id' => $studentUserId,
            //       'created_by' => $payment->created_by,
            //   ]);

            $paymentImage = $payment->paymentImage;
            if ($paymentImage) {
                $paymentImage->payment_id = $newPayment->id;
                $paymentImage->save();
            }

            $payment->deleted_by = Auth::id();
            $payment->save();
            $payment->delete();


            $courseReserve->update([
                'status' => StatusEnum::REGISTERED,
            ]);

            DB::commit();

            return redirect()->route('course-registers.create', ['clue_id' => $courseReserve->clue_id])->with('success', __('course_reserves.messages.successfully_converted'));
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', __('course_reserves.messages.error_occurred_in_convert_reserve'));
        }
    }
}
