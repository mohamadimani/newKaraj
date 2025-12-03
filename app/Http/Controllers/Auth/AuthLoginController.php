<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Clue;
use App\Models\Course;
use App\Models\FamiliarityWay;
use App\Models\Secretary;
use App\Models\User;
use App\Models\VerificationCode;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthLoginController extends Controller
{
    public function loginView(): View
    {
        return view('auth.login');
    }

    public function verifyCodeView(): View|RedirectResponse
    {
        if (session('mobile') && $verificationCodeId = session('verification_code_id')) {
            $verificationCode = VerificationCode::find($verificationCodeId);
            $ttl = $this->calcVerificationCodeExpireTtl($verificationCode);

            return view('auth.verify-code', ['ttl' => $ttl]);
        }

        return redirect(route('login', absolute: false));
    }

    public function sendVerificationCode(Request $request): RedirectResponse
    {
        $request->validate([
            'mobile' => ['required', 'numeric', 'regex:/^09[0-9]{9}$/'],
        ]);

        try {
            if (empty(FIDAR_AI())) {
                throw new Exception(__('login.errors.fidar_ai_not_found'));
            }

            $mobile = formatMobile($request->mobile);
            $user = User::where('mobile', $mobile)->first();

            if (!checkUserIsActive($user)) {
                throw new Exception('این کاربر غیرفعال است');
            }

            if (!$user and $user = $this->createUser($mobile)) {
                $this->createClue($user);
            }

            if (!$user) {
                throw new Exception(__('login.errors.user_not_found'));
            }

            if ($user->is_active == false) {
                throw new Exception(__('login.errors.inactive_user'));
            }

            $info = null;
            $verificationCode = VerificationCode::where('user_id', $user->id)
                ->notExpired()
                ->notHasBeenUsed()
                ->first();
            if ($verificationCode) {
                $info = __('login.errors.verification_code_already_sent');
            } else {
                $otp = 123456;
                $sendSms = false;

                if (env('APP_ENV') !== 'local') {
                    $otp = rand(100000, 999999);
                    $sendSms = true;
                }

                if (in_array($mobile, ['09191930406'])) { // mani
                    $otp = 159852;
                    $sendSms = false;
                }

                if (in_array($mobile, ['09126670587'])) { // badrkhani
                    $otp = '364036';
                    $sendSms = false;
                }

                if (in_array($mobile, ['09380315765'])) { // accounting
                    $otp = '159852';
                    $sendSms = false;
                }

                $verificationCode = VerificationCode::create([
                    'otp' => $otp,
                    'user_id' => $user->id,
                    'mobile' => $mobile,
                    'expires_at' => now()->addMinutes(intval(env('OTP_EXPIRE_TIME_MINUTE', 2))),
                ]);

                if ($sendSms) {
                    sendOtp($mobile, $otp, 'kavehnegar');
                }
            }

            session([
                'mobile' => $request->mobile,
                'verification_code_id' => $verificationCode->id,
            ]);

            return redirect(route('auth.verify-code', absolute: false))
                ->with(['info' => $info]);
        } catch (Exception $e) {
            return redirect()->back()->with([
                'error' => $e->getMessage(),
                'mobile' => $request->mobile,
            ]);
        }
    }

    public function checkVerificationCode(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        try {
            if (!$mobile = session('mobile')) {
                throw new Exception(__('login.errors.empty_mobile'));
            }

            $verificationCode = VerificationCode::where('mobile', $mobile)
                ->notExpired()
                ->notHasBeenUsed()
                ->first();

            if (!$verificationCode) {
                throw new Exception(__('login.errors.no_verification_code_for_mobile'));
            }
            if ($verificationCode->otp != $request->otp) {
                throw new Exception(__('login.errors.wrong_entered_otp'));
            }

            $verificationCode->used_at = now();
            $verificationCode->save();

            $loggedInUser = $verificationCode->user;

            session([
                'mobile' => null,
                'verification_code_id' => null,
                'user' => [
                    'full_name' => $loggedInUser->fullName,
                    'mobile' => $loggedInUser->mobile,
                    'email' => $loggedInUser->email,
                ]
            ]);
            Auth::login($loggedInUser, true);

            return redirect(route('dashboard', absolute: false));
        } catch (Exception $e) {
            return redirect()->back()->with([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

    private function calcVerificationCodeExpireTtl(VerificationCode $verificationCode): int
    {
        return floor(now()->diffInSeconds($verificationCode->expires_at));
    }

    private function createUser(string $mobile): User
    {
        return  User::create([
            'mobile' => $mobile,
            'is_admin' => false,
            'is_active' => true,
            'created_by' => FIDAR_AI(),
        ]);
    }

    private function createClue(User $user): Clue
    {
        $clue = Clue::create([
            'user_id' => $user->id,
            'created_by' => FIDAR_AI(),
            'branch_id' =>  session()->get('branch_id') ?? Branch::where('name', 'online')->first()->id,
            'secretary_id' =>  Secretary::where('user_id', FIDAR_AI())->first()->id,
            'familiarity_way_id' => FamiliarityWay::where('slug', 'site')->first()->id,
        ]);
        $professionId = 1;
        if (session()->get('course_id')) {
            $profession = Course::where('id', session()->get('course_id'))->first()->profession;
            $professionId = $profession->id;
        }
        $clue->professions()->sync([$professionId]);
        return $clue;
    }

    public function loginByUserId(int $userId)
    {
        if (!session()->get('last_login_user_id')) {
            session(['last_login_user_id' => Auth::id()]);
        }
        Auth::loginUsingId($userId);
        return redirect(route('dashboard', absolute: false));
    }
}
