@extends('admin.layouts.guest')

@section('content')
<div class="authentication-wrapper authentication-cover">
    <div class="authentication-inner row m-0">
        @php
        $mobile = Session::get('mobile');
        $type = 'password';
        @endphp
        @if (IsPortal())
        @include('components.guest-cover-logo-portal')
        <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-4 p-sm-5">
            <div class="w-px-400 mx-auto">
                @include('components.guest-small-logo')
                @if (Session::has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('error') }}
                </div>
                @endif
                @if (Session::has('info'))
                <div class="alert alert-info" role="alert">
                    {{ Session::get('info') }}
                </div>
                @endif
                <span class="expire-time-hidden">{{ $ttl ?? 0 }}</span>
                <h4 class="mb-3 secondary-font">تایید دو مرحله‌ای</h4>
                <p class="text-start mb-4">
                    ما یک کد تایید به موبایل شما ارسال کردیم. کد ارسال شده را در فیلد زیر وارد کنید.
                    <span class="fw-bold d-block mt-2">{{ Session::get('mobile') ?? '---' }}
                        <a href="{{ route('login') }}">{{ ' (' . __('login.edit_mobile') . ')' }}</a>
                    </span>
                </p>
                <p class="mb-0 fw-semibold">کد 6 رقمی امنیتی را وارد کنید</p>
                <form id="twoStepsForm" action="{{ route('auth.check-verification-code') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="auth-input-wrapper otp-inputs d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1" autofocus>
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                        </div>
                        <input type="hidden" name="otp" id="otp_input_portal" value="">
                    </div>
                    <button class="btn btn-primary d-grid w-100 mb-3 confirmation-button" disabled>
                        {{ __('login.send_code') }}
                    </button>
                </form>
                <form action="{{ route('auth.send-verification-code') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mobile" value="{{ Session::get('mobile') ?? null }}" />
                    <div class="resend-box text-center d-none">
                        {{ __('login.verification.did_not_get_code') }}
                        <button type="submit" class="resend btn btn-outline-primary btn-sm">
                            {{ __('login.verification.resend_code') }}
                        </button>
                    </div>
                </form>
                <div class="expire-time-box d-none text-center">
                    <span>{{ __('login.remaining_time') }}:</span>
                    <b class="expire-time"></b>
                    <b> {{ __('login.seconds') }}</b>
                </div>
            </div>
        </div>
        @else
        <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-4 p-sm-5">
            <div class="w-px-400 mx-auto">
                @include('components.guest-small-logo')
                @if (Session::has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('error') }}
                </div>
                @endif
                @if (Session::has('info'))
                <div class="alert alert-info" role="alert">
                    {{ Session::get('info') }}
                </div>
                @endif
                <span class="expire-time-hidden">{{ $ttl ?? 0 }}</span>
                <h4 class="mb-3 secondary-font">تایید دو مرحله‌ای</h4>
                <p class="text-start mb-4">
                    ما یک کد تایید به موبایل شما ارسال کردیم. کد ارسال شده را در فیلد زیر وارد کنید.
                    <span class="fw-bold d-block mt-2">{{ Session::get('mobile') ?? '---' }}
                        <a href="{{ route('login') }}">{{ ' (' . __('login.edit_mobile') . ')' }}</a>
                    </span>
                </p>
                <p class="mb-0 fw-semibold">کد 6 رقمی امنیتی را وارد کنید</p>
                <form id="twoStepsForm" action="{{ route('auth.check-verification-code') }}" method="POST" autocomplete="on">
                    @csrf
                    <div class="mb-3">
                        <div class="auth-input-wrapper otp-inputs-my d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1" autofocus>
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="{{ $type }}" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                        </div>
                        <input type="hidden" name="otp" id="otp_input_my" value="">
                    </div>
                    <button class="btn btn-primary d-grid w-100 mb-3 confirmation-button" disabled>
                        {{ __('login.send_code') }}
                    </button>
                </form>
                <form action="{{ route('auth.send-verification-code') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mobile" value="{{ Session::get('mobile') ?? null }}" />
                    <div class="resend-box text-center d-none">
                        {{ __('login.verification.did_not_get_code') }}
                        <button type="submit" class="resend btn btn-outline-primary btn-sm">
                            {{ __('login.verification.resend_code') }}
                        </button>
                    </div>
                </form>
                <div class="expire-time-box d-none text-center">
                    <span>{{ __('login.remaining_time') }}:</span>
                    <b class="expire-time"></b>
                    <b> {{ __('login.seconds') }}</b>
                </div>
            </div>
        </div>
        @include('components.guest-cover-logo-my')
        @endif
    </div>
</div>
<script>
    setInterval(() => {
        var time = $('span.expire-time-hidden').text();
        time = parseInt(time)
        if (time > 0) {
            time = time - 1;
            $('b.expire-time').text(time);
            $('span.expire-time-hidden').text(time)
            $('.expire-time-box').removeClass('d-none')
            $('.confirmation-button').removeAttr('disabled')
        } else {
            $('.expire-time-box').addClass('d-none')
            $('.resend-box').removeClass('d-none')
            $('.confirmation-button').prop('disabled', true)
        }
    }, 1000);


    if ('OTPCredential' in window) {
        window.onload = async () => {
            try {
                const content = await navigator.credentials.get({
                    otp: {
                        transport: ['sms']
                    },
                    signal: new AbortController().signal,
                });
                if (content) {
                    const otpCode = content.code;

                    const inputs = document.querySelectorAll('.otp-inputs input');
                    for (let i = 0; i < inputs.length && i < otpCode.length; i++) {
                        inputs[i].value = otpCode.charAt(i);
                    }
                    const inputsMy = document.querySelectorAll('.otp-inputs-my input');
                    for (let i = 0; i < inputsMy.length && i < otpCode.length; i++) {
                        inputsMy[i].value = otpCode.charAt(i);
                    }

                    const otpInputPortal = document.getElementById('otp_input_portal');
                    const otpInputMy = document.getElementById('otp_input_my');

                    if (otpInputPortal) otpInputPortal.value = otpCode;
                    if (otpInputMy) otpInputMy.value = otpCode;

                    // Automatically submit the form when OTP is filled
                    const form = document.querySelector('form');
                    if (form) {
                        setTimeout(() => {
                            form.submit();
                        }, 100); // Small delay to ensure values are properly set
                    }
                }
            } catch (err) {
                console.log('Error fetching OTP:', err);
            }
        };
    }
</script>
@endsection
