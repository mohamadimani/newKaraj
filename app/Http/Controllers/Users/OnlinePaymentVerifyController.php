<?php

namespace App\Http\Controllers\Users;

use App\Enums\OnlinePayment\StatusEnum;
use App\Enums\Payment\StatusEnum as PaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Clue;
use App\Models\OnlinePayment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MellatBank\MellatBankService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use nusoap_client;

class OnlinePaymentVerifyController extends Controller
{
    public function index()
    {
        $res = null;
        $success = false;
        $SaleReferenceId = null;

        // // for test
        // $RefId = "D16351AF2C0F483D";
        // $ResCode = "0";
        // $SaleOrderId = "1745425445";
        // $SaleReferenceId = "296812639302";
        // $CardHolderInfo = "B4736AE24C1C38EC316846E5DC40514F4A9A978837D79853658C7ED06B498B61";
        // $CardHolderPan = "589210******3970";
        // $FinalAmount = "52500000";

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
            $paymentModel = OnlinePayment::where('RRN', $SaleOrderId)->first();


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

        $order = [];
        if (isset($paymentModel)) {
            $order = Order::where(['id' => $paymentModel->order_id])->first();
            Auth::loginUsingId($order->user_id);
        }

        return view('users.payment-verify.online-course', compact('res', 'success', 'SaleReferenceId', 'order'));
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
            if ($order = Order::where(['id' => $paymentModel->order_id, 'payment_id' => $paymentModel->id])->with('orderItems')->first()) {
                $order->pay_date = time();
                $order->payment_status = StatusEnum::PAID;
                $order->save();

                foreach ($order->orderItems as $orderItem) {
                    // add license key in order item
                    try {
                        $License = $this->License($user->full_name, [$orderItem->spot_key], [$user->mobile], false);

                        $orderItem->license_key = $License['key'];
                        $orderItem->license_url = $License['url'];
                        $orderItem->license_id = $License['_id'];
                        $orderItem->pay_date = time();
                        $orderItem->save();
                    } catch (Exception $e) {
                        echo ($e->getMessage());
                    }

                    $orderItem->onlineCourse->registered_count = $orderItem->onlineCourse->registered_count + 1;
                    $orderItem->onlineCourse->save();
                }
                // ===== update discount  used count  =====
                if ($order->discount_id > 0) {
                    $order->discount->used_count = $orderItem->discount->used_count + 1;
                    $order->discount->save();
                }
                $this->addPayment($order, $paymentModel);
                addClueToStudent($user);
            }
            DB::commit();
            return 'ثبت نام با موفقیت انجام شد!';
        } else {
            DB::rollBack();
            return 'خطایی نامشخص رخ داده است!';
        }
    }

    function request($u, $o = null)
    {
        curl_setopt_array($c = curl_init(), [
            CURLOPT_URL => $u,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $o ? 'POST' : 'GET',
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTPHEADER => ['$API: ' . env('SPOTPLAYER_KEY'), '$LEVEL: -1', 'content-type: application/json'],
        ]);
        if ($o) curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($this->filter($o)));
        $json = json_decode(curl_exec($c), true);
        curl_close($c);
        if (is_array($json) && ($ex = @$json['ex'])) throw new Exception($ex['msg']);
        return $json;
    }

    function license($name, $courses, $watermarks, $test)
    {
        return $this->request('https://panel.spotplayer.ir/license/edit/', [
            'test' => $test,
            'name' => $name,
            'course' => $courses,
            'watermark' => ['texts' => array_map(function ($w) {
                return ['text' => $w];
            }, $watermarks)]
        ]);
    }

    function filter($a): array
    {
        return array_filter($a, function ($v) {
            return !is_null($v);
        });
    }

    protected function addPayment($order, $onlinePaymentModel)
    {
        $payment = Payment::create([
            'paid_amount' => $onlinePaymentModel->paid_amount,
            'paymentable_id' => $order->id,
            'paymentable_type' => Order::class,
            'payment_method_id' => 6,
            'branch_id' => $order->user->clue->branch_id ?? 7,
            'pay_date' => georgianToJalali(date('Y-m-d')),
            'description' => 'پرداخت آنلاین',
            'user_id' => $order->user_id,
            'created_by' => FIDAR_AI(),
            'status' => PaymentStatusEnum::VERIFIED,
            'discount_id' => $order->discount_id,
        ]);

        if ($payment) {
            $description = 'واریز به کیف پول بابت پرداخت درگاه دوره آنلاین';
            addTransaction($payment, $description);
            if ($order->reference_code and $referenceUser = User::where('reference_code', $order->reference_code)->first()) {

                $amount = $payment->paid_amount * 0.1;
                $transaction = Transaction::create([
                    'user_id' => $referenceUser->id,
                    'payment_id' => $payment->id,
                    'amount' => $amount,
                    'description' => 'واریز به کیف پول بابت معرفی دوره آنلاین',
                    'created_by' => FIDAR_AI(),
                ]);
                if ($transaction) {
                    addWallet($referenceUser, $amount);
                }
            }
        }
    }
}
