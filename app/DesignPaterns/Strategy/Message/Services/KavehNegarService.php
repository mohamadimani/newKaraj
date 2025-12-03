<?php

namespace App\DesignPaterns\Strategy\Message\Services;


use App\DesignPaterns\Strategy\Message\MessageInterface;
use Exception;
use Illuminate\Support\Facades\Http;

class KavehNegarService implements MessageInterface
{
    public function sendMessage($to, $message)
    {
        $sender = env('LINE_KAVEHNEGHAR_OWN_LINE');
        $url =  "https://api.kavenegar.com/v1/" . env('TOKEN_KAVEHNEGHAR') . "/sms/send.json?receptor=$to&message=$message&sender=$sender";
        try {
            $response = Http::get($url);
            return  json_decode($response);
        } catch (Exception $e) {
            return [$e];
        }
    }

    public function sendOtp($to, $message)
    {
        $template = request()->getHost() ==  "newDeniz.com" ? 'portalLogin' : 'myLoin'; //for auto fill opt in both domains
        $url =  "https://api.kavenegar.com/v1/" . env('TOKEN_KAVEHNEGHAR') . "/verify/lookup.json?receptor=$to&token=$message&template=$template";
        try {
            $response = Http::get($url);
            return  json_decode($response);
        } catch (Exception $e) {
            return [$e];
        }
    }

    public function sendMessageArray(array $to, array $message)
    {
        $sender = env('LINE_KAVEHNEGHAR_OWN_LINE');
        $url =  "https://api.kavenegar.com/v1/" . env('TOKEN_KAVEHNEGHAR') . "/sms/send.json?receptor=$to&message=$message&sender=$sender";
        try {
            $response = Http::get($url);
            dd($response);
            if ($response) {
                return true;
            }
            return  json_decode($response);
        } catch (Exception $e) {
            return [$e];
        }
    }
}
