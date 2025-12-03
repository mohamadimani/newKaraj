<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaymentStoreRequest;
use App\Models\CourseRegister;
use App\Models\CourseReserve;
use App\Models\Payment;
use App\Models\PaymentImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function index()
    {
        Gate::authorize('index', Payment::class);
        return view('admin.payments.index');
    }

    public function store(PaymentStoreRequest $request)
    {
        Gate::authorize('store', Payment::class);

        $uniqKey = $request->payment_method_id . $request->paymentable_id . $request->paid_amount . Auth::id();
        if ($uniq_key = Cache::get('uniq_key') and $uniq_key === $uniqKey) {
            return redirect()->back();
        }
        Cache::put('uniq_key', $uniqKey, 30);

        $paymentData = [
            ...$request->all(),
            'description' => $request->payment_description ?? null,
            'created_by' => Auth::id(),
        ];

        if ($request->paid_amount < 1000) {
            $paymentData['status'] = 'verified';
        }
        if ($request->paymentable_type == CourseRegister::class) {
            $courseRegister = CourseRegister::find($request->paymentable_id);
            $paymentData['user_id'] = $courseRegister->student->user_id;
            $paymentData['branch_id'] = $courseRegister->course->branch_id;
        }
        if ($request->paymentable_type == CourseReserve::class) {
            $courseReserve = CourseReserve::find($request->paymentable_id);
            $paymentData['user_id'] = $courseReserve->clue->user_id;
            $paymentData['branch_id'] = $courseReserve->clue->branch_id;
        }
        DB::beginTransaction();

        try {
            $payment = Payment::create($paymentData);

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
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', __('payments.successfully_created'));
    }

    public function refund()
    {
        Gate::authorize('index', Payment::class);
        return view('admin.payments.refund');
    }

}
