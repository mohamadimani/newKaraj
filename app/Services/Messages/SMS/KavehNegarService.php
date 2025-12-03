<?php

namespace App\Services\Messages\SMS;

use Exception;
use Illuminate\Support\Facades\Http;

class KavehNegarService
{
    public function sendSimpleSms() {}

    public function sendVerifySms($to, $code)
    {
        $template = request()->getHost() ==  "newDeniz.com" ? 'portalLogin': 'myLoin';
        $url =  "https://api.kavenegar.com/v1/" . env('TOKEN_KAVEHNEGHAR') . "/verify/lookup.json?receptor=$to&token=$code&template=$template";
        try {
            $response = Http::get($url);
            if ($response) {
                return true;
            }
            return  json_decode($response);
        } catch (Exception $e) {
            return [$e];
        }
    }
}
