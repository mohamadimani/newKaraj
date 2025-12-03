<?php

use App\DesignPaterns\Strategy\Message\SendMessage;
use App\DesignPaterns\Strategy\Message\Services\KavehNegarService;
use App\Models\SendSmsLog;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\User;
use App\Services\NumberToWord\NumberToWord;
use App\Services\ResponseService;
use Hekmatinasser\Verta\Verta;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

if (!function_exists('apiResponse')) {
    function apiResponse(): ResponseService
    {
        return app('response');
    }
}

if (!function_exists('user')) {
    function user()
    {
        return Auth::user();
    }
}

function checkNationalCode($meli)
{
    $cDigitLast = substr($meli, strlen($meli) - 1);
    $fMeli = strval(intval($meli));
    if ((str_split($fMeli))[0] == "0" && !(8 <= strlen($fMeli)  && strlen($fMeli) < 10)) return false;
    $nineLeftDigits = substr($meli, 0, strlen($meli) - 1);
    $positionNumber = 10;
    $result = 0;
    foreach (str_split($nineLeftDigits) as $chr) {
        $digit = intval($chr);
        $result += $digit * $positionNumber;
        $positionNumber--;
    }

    $remain = $result % 11;
    $controllerNumber = $remain;
    if (2 <= $remain) {
        $controllerNumber = 11 - $remain;
    }
    return $cDigitLast == $controllerNumber;
}

function requireSign($sign = '*')
{
    echo '<span class="text-danger">' . $sign . '</span>';
}

if (!function_exists('ApiGet')) {
    function ApiGet($url = '', $params = [], $echo = false)
    {
        try {
            $response = Http::withToken(env('DENIZ_API_KEY'))->get($url, $params);
            if ($echo) {
                echo $response;
                dd($response);
            }
            return  json_decode($response);
        } catch (Exception $e) {
            return [$e];
        }
    }
}

if (!function_exists('ApiPost')) {
    function ApiPost($url = '', $params = [], $echo = false)
    {
        try {
            $response = Http::withToken(env('DENIZ_API_KEY'))->post($url, $params);
            if ($echo) {
                echo $response;
                dd($response);
            }
            $response = json_decode($response);
            return $response;
        } catch (Exception $e) {
            return [$e];
        }
    }
}

if (!function_exists('maxTextLength')) {
    function maxTextLength(string $text, int $length = 20): string
    {
        return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
    }
}

if (!function_exists('SaveImage')) {
    function SaveImage($image, $path)
    {
        if ($image) {
            $name = time() . '.' . $image->extension();
            $image->move(public_path('images/admin/' . $path), $name);
            return $name;
        } else {
            return '';
        }
    }
}

if (!function_exists('GetImage')) {
    function GetImage($path)
    {
        if (env('APP_ENV') == 'local') {
            $url =  'images/admin/';
        } else {
            $url = 'public/images/admin/';
        }

        if (file_exists($url . $path)) {
            return asset($url . $path);
        } else {
            return asset($url . 'default.png');
        }
    }
}

if (!function_exists('DeleteImage')) {
    function DeleteImage($path)
    {
        if (is_file(public_path('images/admin/' . $path)) and unlink(public_path('images/admin/' . $path))) {
            return true;
        }
        return false;
    }
}

if (!function_exists('jalaliToTimestamp')) {
    function jalaliToTimestamp(string $jalaliDate)
    {
        return Verta::parse($jalaliDate)->toCarbon()->timestamp;
    }
}

if (!function_exists('georgianToJalali')) {
    function georgianToJalali(string $date, bool $showTime = false, $seperator = '-')
    {
        $timeFormat = $showTime ? ' H:i:s' : '';
        return Verta::instance($date)->format('Y' . $seperator . 'm' . $seperator . 'd' . $timeFormat);
    }
}

if (!function_exists('toGeorgianDate')) {
    function toGeorgianDate(string $date, bool $showTime = false)
    {
        $timeFormat = $showTime ? ' H:i:s' : '';
        return Verta::parse($date)->formatGregorian('Y-m-d' . $timeFormat);
    }
}

if (!function_exists('unformatNumber')) {
    function unformatNumber(null|string|int $number): ?string
    {
        if ($number === null) {
            return null;
        }
        if (is_string($number)) {
            return str_replace(',', '', $number);
        }
        return $number;
    }
}

if (!function_exists('calcIterationNumber')) {
    function calcIterationNumber(LengthAwarePaginator $model, $loop): int
    {
        return ($model->currentpage() - 1) * $model->perpage() + $loop->index + 1;
    }
}

if (!function_exists('formatMobile')) {
    function formatMobile(string $mobile): string
    {
        if (substr($mobile, 0, 1) == '0' && strlen($mobile) === 11) {
            return $mobile;
        }

        if (substr($mobile, 0, 2) == '98' && strlen($mobile) === 12) {
            return '0' . substr($mobile, 2);
        }

        if (substr($mobile, 0, 3) == '+98' && strlen($mobile) === 13) {
            return '0' . substr($mobile, 3);
        }

        if (substr($mobile, 0, 1) == '9' && strlen($mobile) === 10) {
            return '0' . $mobile;
        }

        return $mobile;
    }
}

if (!function_exists('FIDAR_AI')) {
    function FIDAR_AI()
    {
        return User::where([
            'first_name' => 'FIDAR',
            'last_name' => 'AI',
        ])->first()?->id;
    }
}

if (!function_exists('IsMy')) {
    function IsMy()
    {
        if (url('') == 'https://my.newdeniz.com') {
            return true;
        }
        return false;
    }
}

if (!function_exists('IsPortal')) {
    function IsPortal()
    {
        if (url('') == 'https://newdeniz.com') {
            return true;
        }
        return false;
    }
}

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $numberToWord = new NumberToWord();
        return $numberToWord->numberToWords($number);
    }
}

if (!function_exists('ActiveMenu')) {
    function ActiveMenu($route)
    {
        return request()->url() == route($route) ? 'active' : '';
    }
}

if (!function_exists('OpenMenu')) {
    function OpenMenu($route)
    {
        return request()->url() == route($route) ? 'open' : '';
    }
}

if (!function_exists('secondsToTimeString')) {
    function secondsToTimeString($seconds)
    {
        if ($seconds <= 0) {
            return '0 ثانیه';
        }

        $timeString = [];

        $years = floor($seconds / (365 * 24 * 3600));
        if ($years > 0) {
            $timeString[] = $years . ' سال';
            $seconds %= (365 * 24 * 3600);
        }

        $months = floor($seconds / (30 * 24 * 3600));
        if ($months > 0) {
            $timeString[] = $months . ' ماه';
            $seconds %= (30 * 24 * 3600);
        }

        $days = floor($seconds / (24 * 3600));
        if ($days > 0) {
            $timeString[] = $days . ' روز';
            $seconds %= (24 * 3600);
        }

        $hours = floor($seconds / 3600);
        if ($hours > 0) {
            $timeString[] = $hours . ' ساعت';
            $seconds %= 3600;
        }

        $minutes = floor($seconds / 60);
        if ($minutes > 0) {
            $timeString[] = $minutes . ' دقیقه';
            $seconds %= 60;
        }

        if ($seconds > 0 || empty($timeString)) {
            $timeString[] = $seconds . ' ثانیه';
        }

        return implode(' و ', $timeString);
    }
}

if (!function_exists('isAdminNumber')) {
    function isAdminNumber()
    {
        return in_array(Auth::user()->mobile, ['09191930406', '09912023029', '09126670587']);
    }
}

if (!function_exists('checkUserIsActive')) {
    function checkUserIsActive($user)
    {
        if ($user?->secretary != null and !$user->secretary?->is_active) {
            return false;
        }
        if ($user?->clerk != null and !$user->clerk?->is_active) {
            return false;
        }
        if ($user?->teacher != null and !$user->teacher?->is_active) {
            return false;
        }
        return true;
    }
}

if (!function_exists('sendMessage')) {
    function sendMessage($user, $text, $service)
    {
        $message = new SendMessage();
        if ($service == 'kavehnegar') {
            $message->setService(new KavehNegarService());
        }
        $message->setReceiver($user->mobile);
        $message->setMessage($text);
        $res = $message->sendMessage();
        if ($res) {
            SendSmsLog::setSmsLog($user->id, $user->mobile, $text);
        }
        return $res;
    }
}

if (!function_exists('sendOtp')) {
    function sendOtp($mobile, $otp, $service)
    {
        $message = new SendMessage();
        if ($service == 'kavehnegar') {
            $message->setService(new KavehNegarService());
        }
        $message->setReceiver($mobile);
        $message->setMessage($otp);
        return $message->sendOtp();
    }
}

if (!function_exists('userSearchQuery')) {
    function userSearchQuery($query, $search)
    {
        $search = trim($search);
        return $query->where('first_name', 'like', '%' . $search . '%')
            ->orWhere('last_name', 'like', '%' . $search . '%')
            ->orWhere('mobile', 'like', '%' . $search . '%')
            ->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', '%' . $search . '%');
    }
}

if (!function_exists('addClueToStudent')) {
    function addClueToStudent($user)
    {
        $clue = $user->clue;
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            $student = Student::create([
                'user_id' => $user->id,
                'created_by' => FIDAR_AI(),
            ]);
        }
        $clue->update([
            'student_id' => $student->id,
        ]);
    }
}

if (!function_exists('militaryStatus')) {
    function militaryStatus()
    {
        return [
            '1' => 'پایان خدمت',
            '2' => 'معافیت دائم',
            '3' => 'معافیت تحصیلی',
            '4' => 'در حال انجام',
            '5' => 'مشمول',
            '6' => 'خانم هستم',
        ];
    }
}

if (!function_exists('addTransaction')) {
    function addTransaction($payment, $description = null, $persent = 0.1)
    {
        $amount = $payment->paid_amount * $persent;
        $transaction = Transaction::create([
            'user_id' => $payment->user_id,
            'payment_id' => $payment->id,
            'amount' => $amount,
            'description' => $description,
            'created_by' => Auth::id() ?? FIDAR_AI(),
        ]);
        if ($transaction and $user = User::find($payment->user_id)) {
            addWallet($user, $amount);
        }
        return $transaction;
    }
}

if (!function_exists('withdrawTransaction')) {
    function withdrawTransaction($payment, $description = null, $persent = 0.1)
    {
        $amount = $payment->paid_amount * $persent;
        $transaction = Transaction::create([
            'user_id' => $payment->user_id,
            'payment_id' => $payment->id,
            'amount' => -$amount,
            'description' => $description,
            'created_by' => Auth::id() ?? FIDAR_AI(),
        ]);
        if ($transaction and $user = User::find($payment->user_id)) {
            withdrawWallet($user, $amount);
        }
        return $transaction;
    }
}

if (!function_exists('addWallet')) {
    function addWallet($user, $amount)
    {
        $user->wallet = $user->wallet + $amount;
        return $user->save();
    }
}

if (!function_exists('withdrawWallet')) {
    function withdrawWallet($user, $amount)
    {
        $user->wallet = $user->wallet - $amount;
        return $user->save();
    }
}
