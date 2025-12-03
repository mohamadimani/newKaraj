<?php

namespace App\Http\Controllers\Users;

use App\Enums\OnlinePayment\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\CourseOrder;
use App\Models\CoursePayment;
use App\Services\MellatBank\MellatBankService;
use Exception;
use Illuminate\Support\Facades\Auth;
use nusoap_client;

class CourseOrderController extends Controller
{
    public function index()
    {
        // return view('users.course-orders.index');
    }

    public function show($courseOrderId)
    {
        $courseOrder = CourseOrder::find($courseOrderId);
        return view('users.course-orders.show', compact('courseOrder'));
    }

    public function pay($courseOrderId)
    {
        $selOrderId = time();
        $courseOrder = CourseOrder::find($courseOrderId);
        $coursePayment = $this->addPayment($courseOrder, $selOrderId);
        // add payment id in order
        $courseOrder->payment_id = $coursePayment->id;
        $courseOrder->save();

        $inRialAmount = $courseOrder->total_amount * 10; // convert to rial
        $params = MellatBankService::SetMellatPaymentParams($inRialAmount,  $coursePayment->RRN, 'payment for course order', route('user.course-orders.pay-verify'));

        $client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $client->setCurlOption(CURLOPT_CONNECTTIMEOUT, 0);
        $err = $client->getError();

        if ($err) {
            $this->addErrorForPayment($coursePayment->id, $err);
        }
        try {
            $result = $client->call('bpPayRequest', $params, 'http://interfaces.core.sw.bps.com/');
            $res = explode(',', $result);
            $ResCode = $res[0];
            if (isset($res[1])) {
                $RefId = $res[1];
            } else {
                return ['error' => MellatBankService::error($ResCode)];
            }

            if ($ResCode == "0") {
                $this->addTokenForPayment($coursePayment->id, $RefId);
                return $this->postRefId($RefId);
            } else {
                return ['error' => MellatBankService::error($ResCode)];
            }
        } catch (Exception $ex) {
            $this->addErrorForPayment($coursePayment->id, $ex->getMessage());
            return ['error' => MellatBankService::error($ex->getMessage())];
        }
    }

    public function addPayment($courseOrder, $selOrderId)
    {
        if ($coursePayment = CoursePayment::where(['course_order_id'=> $courseOrder->id , 'user_id' => Auth::id()])->first()) {
            return $coursePayment;
        }

        return CoursePayment::create([
            'user_id' => Auth::id(),
            'course_order_id' => $courseOrder->id,
            'amount' => $courseOrder->total_amount,
            'created_by' => FIDAR_AI(),
            'RRN' => $selOrderId,
        ]);
    }

    public function  addErrorForPayment($coursePaymentId, $error)
    {
        $coursePayment = CoursePayment::find($coursePaymentId);
        $coursePayment->bank_error = $error;
        $coursePayment->status = StatusEnum::CANCELED->value;
        return $coursePayment->save();
    }

    public function  addTokenForPayment($coursePaymentId, $refId)
    {
        $coursePayment = CoursePayment::find($coursePaymentId);
        $coursePayment->token = $refId;
        return $coursePayment->save();
    }

    protected function postRefId($refIdValue)
    {
        echo '<script>window.location.href = "https://bpm.shaparak.ir/pgwchannel/startpay.mellat?RefId=' . $refIdValue . '";</script>';
        exit();
    }
}
