<?php

namespace App\Services\MellatBank;

use Exception;
use Illuminate\Support\Facades\Auth;
use nusoap_client;

/**
 * کلاس درگاه پرداخت بانک ملت
 *
 * با استفاده از این کلاس میتوانید به راحتی از درگاه پرداخت بانک ملت
 * در نرم افزار های تحت وب خود استفاده کنید، همچنین میتوایند از این
 * کلاس در سی ام اس هایی مانند وردپرس ، جوملا و .. نیز استفاده کنید.
 *
 * @category  Gateway
 * @package   MellatPayService
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @example   ../index.php
 * @example <br />
 *  $mellat = new MellatPayService();<br />
 *  $mellat->startPayment('1000', 'http://localhost');<br />
 *  $results = $mellat->checkPayment($_POST);<br />
 *  if($results['status']=='success') echo 'OK';<br />
 * @version   1
 * @since     2014-12-10
 * @author    Hasan Shafei [ www.netparadis.com ]
 */
class MellatBankService
{
    public $terminal;
    public $username;
    public $password;

    public function __construct()
    {
        $this->terminal = env('BEH_PARDAKHT_TERMINAL_ID');
        $this->username = env('BEH_PARDAKHT_USERNAME');
        $this->password = env('BEH_PARDAKHT_PASSWORD');
    }

    /**
     * تابع پرداخت
     * با استفاده از این متد میتوانید درخواست پرداخت را به بانک ملت ارسال کنید.
     *
     * @param intiger $amount : مبلغ پرداخت
     * @param string $callBackUrl : آدرس برگشت بعد از پرداخت
     *

     * @author  Hasan Shafei [ www.netparadis.com ]
     */
    public function startPayment($amount, $callBackUrl, $additionalData, $orderId)
    {
        $client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $client->setCurlOption(CURLOPT_CONNECTTIMEOUT, 0);
        $err = $client->getError();

        if ($err) {
            return ['error' => $err];
        }
        $parameters = array(
            'terminalId' => $this->terminal,
            'userName' => $this->username,
            'userPassword' => $this->password,
            'orderId' => $orderId,
            'amount' => $amount,
            'localDate' => date('ymj'),
            'localTime' => date('His'),
            'additionalData' => $additionalData,
            'callBackUrl' => $callBackUrl,
            'payerId' => auth()->user()->id
        );

        try {
            $result = $client->call('bpPayRequest', $parameters, 'http://interfaces.core.sw.bps.com/');

            $res = explode(',', $result);
            $ResCode = $res[0];
            $RefId = $res[1];
            if ($ResCode == "0") {
                return $this->postRefId($RefId);
            } else {
                return ['error' => $this->error($ResCode)];
            }
        } catch (Exception $ex) {
            $err_msg =  $ex->getMessage();
            return ['error' => $err_msg];
        }
    }

    /**
     * تابع تایید پرداخت
     * با استفاده از این تابع میتوانید درخواست تایید پرداخت را
     * به بانک ملت ارسال کنید و پاسخ آن را دریافت کنید.
     *
     * @param array $params : اطلاعات دریافتی از درگاه پرداخت
     *
     * @return  void
     *

     * @author  Hasan Shafei [ www.netparadis.com ]
     */
    protected function verifyPayment($params)
    {
        $client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $client->setCurlOption(CURLOPT_CONNECTTIMEOUT, 0);
        $orderId = $params["SaleOrderId"];
        $verifySaleOrderId = $params["SaleOrderId"];
        $verifySaleReferenceId = $params['SaleReferenceId'];
        $err = $client->getError();
        if ($err) {
            echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            die();
        }
        $parameters = array(
            'terminalId' => $this->terminal,
            'userName' => $this->username,
            'userPassword' => $this->password,
            'orderId' => $orderId,
            'saleOrderId' => $verifySaleOrderId,
            'saleReferenceId' => $verifySaleReferenceId
        );
        $result = $client->call('bpVerifyRequest', $parameters, 'http://interfaces.core.sw.bps.com/');
        if ($client->fault) {
            echo '<h2>Fault</h2><pre>';
            print_r($result);
            echo '</pre>';
            die();
        } else {
            $resultStr = $result;
            $err = $client->getError();
            if ($err) {
                echo '<h2>Error</h2><pre>' . $err . '</pre>';
                die();
            } else {
                if ($resultStr == '0') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * تابع درخواست تصفیه حساب
     * با استفاده از این تابع میتوانید درخواست تصفیه حساب
     * را به بانک ملت ارسال و نتیجه آن را دریافت کنید.
     *
     * @param array $params : اطلاعات دریافتی از درگاه پرداخت
     *
     * @return  void
     *
     * @author  Hasan Shafei [ www.netparadis.com ]
     */
    protected function settlePayment($params)
    {
        $client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $client->setCurlOption(CURLOPT_CONNECTTIMEOUT, 0);
        $orderId = $params["SaleOrderId"];
        $settleSaleOrderId = $params["SaleOrderId"];
        $settleSaleReferenceId = $params['SaleReferenceId'];
        $err = $client->getError();
        if ($err) {
            echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            die();
        }
        $parameters = array(
            'terminalId' => $this->terminal,
            'userName' => $this->username,
            'userPassword' => $this->password,
            'orderId' => $orderId,
            'saleOrderId' => $settleSaleOrderId,
            'saleReferenceId' => $settleSaleReferenceId
        );
        $result = $client->call('bpSettleRequest', $parameters, 'http://interfaces.core.sw.bps.com/');
        if ($client->fault) {
            echo '<h2>Fault</h2><pre>';
            print_r($result);
            echo '</pre>';
            die();
        } else {
            $resultStr = $result;
            $err = $client->getError();
            if ($err) {
                echo '<h2>Error</h2><pre>' . $err . '</pre>';
                die();
            } else {
                if ($resultStr == '0') {
                    return true;
                }
                return $resultStr;
            }
        }
        return false;
    }

    /**
     * تابع بررسی ترانش
     * با استفاده از این تابع میتوانید درخواست تایید و تصفیه حساب را
     * ارسال کنید و از نتیجه آن آگاه شوید.
     *
     * @param array $params : اطلاعات دریافتی از درگاه پرداخت
     *
     * @return  void
     *
     * @author  Hasan Shafei [ www.netparadis.com ]
     */
    public function checkPayment($params)
    {
        $params["RefId"] = $params["RefId"];
        $params["ResCode"] = $params["ResCode"];
        $params["SaleOrderId"] = $params["SaleOrderId"];
        $params["SaleReferenceId"] = $params["SaleReferenceId"];
        if ($params["ResCode"] == 0) {
            if ($this->verifyPayment($params) == true) {
                if ($this->settlePayment($params) == true) {
                    return array(
                        "status" => "success",
                        "trans" => $params["SaleReferenceId"]
                    );
                }
            }
        }
        return false;
    }

    protected function postRefId($refIdValue)
    {
        echo '<script>window.location.href = "https://bpm.shaparak.ir/pgwchannel/startpay.mellat?RefId=' . $refIdValue . '";</script>';
        exit();
    }

    public static function error($number)
    {
        $err = self::response($number);
        echo '<!doctype html><html><head><meta charset="utf-8"><title>خطا</title></head><body dir="rtl">';
        echo '<style>div.error{direction:rtl;background:#A80202;float:center;text-align:center;color:#fff;';
        echo 'font-family:tahoma;font-size:13px;padding:3px 10px}</style>';
        echo '<div class="error"><strong>خطا</strong> : ' . $err . '</div>';
        die;
    }

    public static function response($number)
    {
        $errorText = '';
        switch ($number) {
            case "0":
                $errorText = "تراکنش با موفقيت انجام شد";
                break;
            case "11":
                $errorText = "شماره کارت نامعتبر است";
                break;
            case "12":
                $errorText = "موجودی کافي نيست";
                break;
            case "13":
                $errorText = "رمز نادرست است";
                break;
            case "14":
                $errorText = "تعداد دفعات وارد کردن رمز بيش از حد مجاز است";
                break;
            case "15":
                $errorText = "کارت نامعتبر است";
                break;
            case "16":
                $errorText = "دفعات برداشت وجه بيش از حد مجاز است";
                break;
            case "17":
                $errorText = "کاربر از انجام تراکنش منصرف شده است";
                break;
            case "18":
                $errorText = "تاريخ انقضای کارت گذشته است";
                break;
            case "19":
                $errorText = "مبلغ برداشت وجه بيش از حد مجاز است";
                break;
            case "111":
                $errorText = "صادر کننده کارت نامعتبر است";
                break;
            case "112":
                $errorText = "خطای سوييچ صادر کننده کارت";
                break;
            case "113":
                $errorText = "پاسخي از صادر کننده کارت دريافت نشد";
                break;
            case "114":
                $errorText = "دارنده کارت مجاز به انجام اين تراکنش نيست";
                break;
            case "21":
                $errorText = "پذيرنده نامعتبر است";
                break;
            case "23":
                $errorText = "خطای امنيتي رخ داده است";
                break;
            case "24":
                $errorText = "اطلاعات کاربری پذيرنده نامعتبر است";
                break;
            case "25":
                $errorText = "مبلغ نامعتبر است";
                break;
            case "31":
                $errorText = "پاسخ نامعتبر است";
                break;
            case "32":
                $errorText = "فرمت اطلاعات وارد شده صحيح نمي باشد";
                break;
            case "33":
                $errorText = "حساب نامعتبر است";
                break;
            case "34":
                $errorText = "خطای سيستمي";
                break;
            case "35":
                $errorText = "تاريخ نامعتبر است";
                break;
            case "41":
                $errorText = "شماره درخواست تکراری است";
                break;
            case "42":
                $errorText = "تراکنش  يافت نشد";
                break;
            case "43":
                $errorText = "قبلا درخواست اعتبارسنجی داده شده است";
                break;
            case "44":
                $errorText = "درخواست اعتبارسنجی يافت نشد";
                break;
            case "45":
                $errorText = "تراکنش Settle شده است";
                break;
            case "46":
                $errorText = "تراکنش Settle نشده است";
                break;
            case "47":
                $errorText = "تراکنش Settle يافت نشد";
                break;
            case "48":
                $errorText = "تراکنش Reverse شده است";
                break;
            case "412":
                $errorText = "شناسه قبض نادرست است";
                break;
            case "413":
                $errorText = "شناسه پرداخت نادرست است";
                break;
            case "414":
                $errorText = "سازمان صادر کننده قبض نامعتبر است";
                break;
            case "415":
                $errorText = "زمان جلسه کاری به پايان رسيده است";
                break;
            case "416":
                $errorText = "خطا در ثبت اطلاعات";
                break;
            case "417":
                $errorText = "شناسه پرداخت کننده نامعتبر است";
                break;
            case "418":
                $errorText = "اشکال در تعريف اطلاعات مشتری";
                break;
            case "419":
                $errorText = "تعداد دفعات ورود اطلاعات از حد مجاز گذشته است";
                break;
            case "421":
                $errorText = " IP نامعتبر است";
                break;
            case "51":
                $errorText = "تراکنش تکراری است";
                break;
            case "54":
                $errorText = "تراکنش مرجع موجود نيست";
                break;
            case "55":
                $errorText = "تراکنش نامعتبر است";
                break;
            case "61":
                $errorText = "خطا در واريز";
                break;
            default:
                $errorText = "خطای ناشناخته";
                break;
        }
        return $errorText;
    }

    public static function SetMellatPaymentParams($amount, $selOrderId, $descreption, $callBackUrl)
    {
        return [
            'terminalId' => env('BEH_PARDAKHT_TERMINAL_ID'),
            'userName' => env('BEH_PARDAKHT_USERNAME'),
            'userPassword' => env('BEH_PARDAKHT_PASSWORD'),
            'orderId' => $selOrderId,
            'amount' => $amount,
            'localDate' => date('ymj'),
            'localTime' => date('His'),
            'additionalData' => $descreption,
            'callBackUrl' => $callBackUrl,
            'payerId' => Auth::id()
        ];
    }
}
