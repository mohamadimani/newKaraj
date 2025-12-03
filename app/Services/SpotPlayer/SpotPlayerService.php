<?php

namespace App\Services\SpotPlayer;

use Exception;

class SpotPlayerService
{
    public static function request($url, $params = null)
    {
        curl_setopt_array($c = curl_init(), [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $params ? 'POST' : 'GET',
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTPHEADER => ['$API: ' . env('SPOTPLAYER_KEY'), '$LEVEL: -1', 'content-type: application/json'],
        ]);
        if ($params) curl_setopt($c, CURLOPT_POSTFIELDS, json_encode(self::filter($params)));
        $json = json_decode(curl_exec($c), true);
        curl_close($c);
        if (is_array($json) && ($ex = @$json['ex'])) throw new Exception($ex['msg']);
        return $json;
    }

    public static function license($name, $courses, $watermarks, $test)
    {
        return self::request('https://panel.spotplayer.ir/license/edit/', [
            'test' => $test,
            'name' => $name,
            'course' => $courses,
            'watermark' => ['texts' => array_map(function ($w) {
                return ['text' => $w];
            }, $watermarks)]
        ]);
    }

    public static function filter($params): array
    {
        return array_filter($params, function ($param) {
            return !is_null($param);
        });
    }
}
