<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\OnlinePayment;
use App\Models\Order;
use App\Services\MellatBank\MellatBankService;
use Illuminate\Support\Facades\Auth;
use nusoap_client;
use App\Enums\OnlinePayment\StatusEnum;
use Exception;

class OrderController extends Controller
{
    public function index()
    {
        return view('users.orders.index');
    }

    public function show(Order $order)
    {
        return view('users.orders.show', compact('order'));
    }

    public function pay(Order $order)
    {
        $selOrderId = time();
        $onlinePayment = $this->addPayment($order, $selOrderId);
        // add payment id in order
        $order->payment_id = $onlinePayment->id;
        $order->save();
        $paidAmountSum = $order->onlinePayments()->where('pay_confirm', true)->sum('paid_amount');
        $inRialAmount = ($order->final_amount - $paidAmountSum) * 10; // convert to rial
        $params = MellatBankService::SetMellatPaymentParams($inRialAmount,  $onlinePayment->RRN, 'payment for online course', route('user.orders.pay-verify'));

        $client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $client->setCurlOption(CURLOPT_CONNECTTIMEOUT, 0);
        $err = $client->getError();

        if ($err) {
            $this->addErrorForPayment($onlinePayment->id, $err);
        }
        try {
            $result = $client->call('bpPayRequest', $params, 'http://interfaces.core.sw.bps.com/');
            $RefId = 1;
            $res = explode(',', $result);
            $ResCode = $res[0];
            if (isset($res[1])) {
                $RefId = $res[1];
            } else {
                return ['error' => MellatBankService::error($ResCode)];
            }

            if ($ResCode == "0") {
                $this->addTokenForPayment($onlinePayment->id, $RefId);
                return $this->postRefId($RefId);
            } else {
                return ['error' => MellatBankService::error($ResCode)];
            }
        } catch (Exception $ex) {
            $this->addErrorForPayment($onlinePayment->id, $ex->getMessage());
            return ['error' => MellatBankService::error($ex->getMessage())];
        }
    }

    public function addPayment($order, $selOrderId)
    {
        if ($onlinePayment = OnlinePayment::where(['order_id' => $order->id, 'user_id' => Auth::id(), 'status' => 'pending'])->first()) {
            return $onlinePayment;
        }
        $paidAmountSum = $order->onlinePayments()->where('pay_confirm', true)->sum('paid_amount');
        return OnlinePayment::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'amount' => ($order->final_amount - $paidAmountSum),
            'created_by' => FIDAR_AI(),
            'RRN' => $selOrderId,
        ]);
    }

    public function  addErrorForPayment($onlinePaymentId, $error)
    {
        $onlinePayment = onlinePayment::find($onlinePaymentId);
        $onlinePayment->bank_error = $error;
        $onlinePayment->status = StatusEnum::CANCELED->value;
        return $onlinePayment->save();
    }

    public function  addTokenForPayment($onlinePaymentId, $refId)
    {
        $onlinePayment = onlinePayment::find($onlinePaymentId);
        $onlinePayment->token = $refId;
        return $onlinePayment->save();
    }

    protected function postRefId($refIdValue)
    {
        echo '<script>window.location.href = "https://bpm.shaparak.ir/pgwchannel/startpay.mellat?RefId=' . $refIdValue . '";</script>';
        exit();
    }
}
