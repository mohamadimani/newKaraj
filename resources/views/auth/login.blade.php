@extends('admin.layouts.guest')

@section('content')
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            @if (IsPortal())
                @include('components.guest-cover-logo-portal')
                <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
                    <div class="w-px-400 mx-auto">
                        @include('components.guest-small-logo')
                        @if (Session::has('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <h4 class="mb-3 secondary-font">{{ __('login.welcome_message_portal') }}</h4>
                        <p class="mb-4">{{ __('login.hint_message') }}</p>
                        <form id="formAuthentication" class="mb-3" action="{{ route('auth.send-verification-code') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="mobile" class="form-label">{{ __('login.mobile') }}</label>
                                <input type="text" class="form-control text-start" id="mobile" name="mobile" placeholder="{{ __('login.mobile_placeholder') }}"
                                    value="{{ old('mobile') ?? Session::get('mobile') }}" autofocus dir="ltr">
                            </div>
                            @if ($errors->any())
                                <div>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li class="text-danger">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <button class="btn btn-primary d-grid w-100 mt-4">{{ __('login.login_button') }}</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
                    <div class="w-px-400 mx-auto">
                        @include('components.guest-small-logo')
                        @if (Session::has('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <h4 class="mb-3 secondary-font">{{ __('login.welcome_message_my') }}</h4>
                        <p class="mb-4">{{ __('login.hint_message') }}</p>
                        <form id="formAuthentication" class="mb-3" action="{{ route('auth.send-verification-code') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="mobile" class="form-label">{{ __('login.mobile') }}</label>
                                <input type="text" class="form-control text-start" id="mobile" name="mobile" placeholder="{{ __('login.mobile_placeholder') }}"
                                    value="{{ old('mobile') ?? Session::get('mobile') }}" autofocus dir="ltr">
                            </div>
                            @if ($errors->any())
                                <div>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li class="text-danger">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <button class="btn btn-primary d-grid w-100 mt-4">{{ __('login.login_button') }}</button>
                        </form>
                        @include('components.version-text')
                    </div>
                </div>
                @include('components.guest-cover-logo-my')
            @endif
        </div>
    </div>
@endsection
