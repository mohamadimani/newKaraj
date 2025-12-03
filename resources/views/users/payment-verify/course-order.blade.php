@extends('admin.layouts.guest')

@section('content')
    <div class="authentication-wrapper authentication-basic px-4">
        <div class="authentication-inner py-4">
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class=" mb-4  d-inline-block text-center w-100">
                        <a class=" text-center">
                            <span>
                                <img src="{{ asset('admin-panel/assets/img/logo/logo.jfif') }}" alt="" class="" style="width: 35px;">
                                <title>{{ __('public.company_name') }}</title>
                            </span>
                            <span class="app-brand-text demo h3 mb-0 fw-bold">{{ __('public.company_name') }}</span>
                        </a>
                    </div>
                    @if (isset($courseOrder) and isset($courseOrder->id))
                        @if ($success)
                            <h5 class="alert alert-success">{{ $res }}</h5>
                            <p class="text-center font-bold">شناسه پرداخت : {{ $SaleReferenceId }} </p>
                            <p class="text-center font-bold">پرداخت سفارش شماره : {{ $courseOrder->id }} </p>
                            <p class="text-center font-bold">نتیجه پرداخت : <span class="alert alert-success">موفق</span></p>
                        @else
                            <h5 class="alert alert-danger text-center">{{ $res }}</h5>
                            <p class="text-center font-bold">پرداخت سفارش شماره : {{ $courseOrder->id }} </p>
                            <p class="text-center font-bold">نتیجه پرداخت : <span class="alert alert-danger">ناموفق</span></p>
                        @endif
                        <a class="btn btn-primary w-100 my-3" href="{{ route('user.course-orders.show', [$courseOrder->id]) }}">بازگشت</a>
                    @else
                        <h5 class="alert alert-danger">{{ $res }}</h5>
                        <a class="btn btn-primary w-100 my-3" href="{{ route('login') }}">بازگشت</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
