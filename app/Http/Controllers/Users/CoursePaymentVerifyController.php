<?php

namespace App\Http\Controllers\Users;

use App\Enums\OnlinePayment\StatusEnum;
use App\Enums\Payment\StatusEnum as PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Clue;
use App\Models\CourseOrder;
use App\Models\CoursePayment;
use App\Models\CourseRegister;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use App\Services\MellatBank\MellatBankService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use nusoap_client;

class CoursePaymentVerifyController extends Controller
{
    public function index()
    {
        $res = null;
        $success = false;
        $SaleReferenceId = null;

        // // for test
        // $RefId = "D16351AF2C0F483D";
        // $ResCode = "0";
        // $SaleOrderId = "1746436243";
        // $SaleReferenceId = "296812639302";
        // $CardHolderInfo = "B4736AE24C1C38EC316846E5DC40514F4A9A978837D79853658C7ED06B498B61";
        // $CardHolderPan = "589210******3970";
        // $FinalAmount = "5000000";

        // $_POST["ResCode"] = $ResCode;
        // $_POST["RefId"] = $RefId;
        // $_POST["SaleReferenceId"] = $SaleReferenceId;
        // $_POST["SaleOrderId"] = $SaleOrderId;
        // $_POST["CardHolderInfo"] = $CardHolderInfo;
        // $_POST["CardHolderPan"] = $CardHolderPan;
        // $_POST["FinalAmount"] = $FinalAmount;


        if ($_POST) {
            $RefId = $_POST["RefId"];
            $ResCode = $_POST["ResCode"];
            $SaleOrderId = $_POST["SaleOrderId"];
            $paymentModel = CoursePayment::where('RRN', $SaleOrderId)->first();


            if ($_POST["ResCode"] == 0) {
                $SaleReferenceId = $_POST["SaleReferenceId"];
                $CardHolderInfo = $_POST["CardHolderInfo"];
                $CardHolderPan = $_POST["CardHolderPan"];
                $FinalAmount = $_POST["FinalAmount"];

                $confirmServices = $this->confirmServices($SaleOrderId, $SaleReferenceId);
                if ($confirmServices == '0') {
                    $this->setPaymentInfo($paymentModel, $SaleReferenceId, $CardHolderInfo, $CardHolderPan, $FinalAmount);
                    $success = true;
                    $res = MellatBankService::response($confirmServices);
                } else {
                    $res = MellatBankService::response($confirmServices);
                }
            } else {
                $res  = MellatBankService::response($ResCode);
            }
        } else {
            $res  = "پاسخی از سمت بانک ارسال نشده است. ";
        }

        if (!empty(trim($res))) {
            if (isset($paymentModel)) {
                $paymentModel->bank_error = $res;
                $paymentModel->save();
            }
        }

        $courseOrder = [];
        if (isset($paymentModel)) {
            $courseOrder = CourseOrder::where(['id' => $paymentModel->course_order_id])->first();
            Auth::loginUsingId($courseOrder->user_id);
        }

        return view('users.payment-verify.course-order', compact('res', 'success', 'SaleReferenceId', 'courseOrder'));
    }

    public function confirmServices($SaleOrderId, $saleReferenceId)
    {
        $client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $client->setCurlOption(CURLOPT_CONNECTTIMEOUT, 0);

        $err = $client->getError();
        if ($err) {
            return ['error' => $err];
        }
        $parameters = array(
            'terminalId' => env('BEH_PARDAKHT_TERMINAL_ID'),
            'userName' => env('BEH_PARDAKHT_USERNAME'),
            'userPassword' => env('BEH_PARDAKHT_PASSWORD'),
            'orderId' => $SaleOrderId,
            'saleOrderId' => $SaleOrderId,
            'saleReferenceId' => $saleReferenceId
        );
        return $client->call('bpVerifyRequest', $parameters, 'http://interfaces.core.sw.bps.com/');
    }

    protected function setPaymentInfo($paymentModel, $SaleReferenceId, $CardHolderInfo, $CardHolderPan, $FinalAmount)
    {
        DB::beginTransaction();
        if ($user = User::find($paymentModel->user_id) and $paymentModel->pay_confirm == false) {
            $Amount = $FinalAmount / 10; // convert rial to toman

            $paymentModel->status = StatusEnum::PAID;
            $paymentModel->bank_error = null;
            $paymentModel->pay_confirm = true;
            $paymentModel->paid_amount = $Amount;
            $paymentModel->description = json_encode([$SaleReferenceId, $CardHolderInfo, $CardHolderPan]);
            $paymentModel->save();

            // ===== order table data after payment =====
            if ($order = CourseOrder::where(['id' => $paymentModel->course_order_id, 'payment_id' => $paymentModel->id])->with('courseOrderItems')->first()) {
                $order->pay_date = time();
                $order->payment_status = StatusEnum::PAID;
                $order->save();

                foreach ($order->courseOrderItems as $orderItem) {
                    $orderItem->pay_date = time();
                    $orderItem->save();

                    $this->addCourseRegister($orderItem, $user, $paymentModel);

                    // ===== update discount  used count  =====
                    if ($orderItem->discount_id > 0) {
                        $orderItem->discount->used_count = $orderItem->discount->used_count + 1;
                        $orderItem->discount->save();
                    }
                }
            }
            DB::commit();
            return 'ثبت نام با موفقیت انجام شد!';
        } else {
            DB::rollBack();
            return 'خطایی نامشخص رخ داده است!';
        }
    }

    protected function addCourseRegister($orderItem, $user, $CoursePaymentModel)
    {
        $clue = Clue::find($user->clue->id);
        $student = Student::where('user_id', $user->id)->first();
        $isFirstRegister = false;
        if (!$student) {
            $student = Student::create([
                'user_id' => $user->id,
                'created_by' => FIDAR_AI(),
            ]);
            $isFirstRegister = true;
        }

        $clue->update([
            'student_id' => $student->id,
        ]);

        $secretary = $clue->secretary ?? User::where('last_name', 'AI')->first()->secretary;

        $courseRegister = CourseRegister::create([
            'student_id' => $student->id,
            'course_id' => $orderItem->course_id,
            'internal_branch_id' => $clue->branch_id,
            'secretary_id' => $secretary->id,
            'description' => 'ثبت نام و پرداخت آنلاین',
            'created_by' => FIDAR_AI(),
            'is_first_register' => $isFirstRegister,
            'is_paid' => $orderItem->final_amount == $CoursePaymentModel->paid_amount ? true : false,
            'amount' => $orderItem->course->price,
            'paid_amount' => $orderItem->final_amount,
        ]);

        $clue->professions()->wherePivot('profession_id', $courseRegister->course->profession_id)->update([
            'course_register_id' => $courseRegister->id,
        ]);

        $payment = Payment::create([
            'paid_amount' => $orderItem->final_amount,
            'user_id' => $user->id,
            'status' => PaymentStatusEnum::VERIFIED,
            'paymentable_type' => CourseRegister::class,
            'paymentable_id' => $courseRegister->id,
            'payment_method_id' => 6,
            'pay_date' => georgianToJalali(date('Y-m-d')),
            'description' =>  'ثبت نام و پرداخت آنلاین',
            'created_by' => FIDAR_AI(),
            'discount_id' => $orderItem->discount_id,
            'branch_id' => $clue->branch_id,
        ]);

        if ($payment) {
            $description = 'واریز به کیف پول بابت پرداخت درگاه دوره حضوری';
            addTransaction($payment, $description);
        }

        return $courseRegister;
    }
}
