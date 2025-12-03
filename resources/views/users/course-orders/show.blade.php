@extends('users.layouts.master')

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('user.orders.index') }}" class="btn btn-gray">
                    <i class="bx bx-arrow-back me-1"></i>
                    بازگشت به لیست سفارشات
                </a>
            </div>
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                        <div class="mb-xl-0 mb-4">
                            <div class="d-flex align-items-center svg-illustration mb-2 gap-2">
                                <img src="{{ asset('admin-panel/assets/img/logo/logo.jfif') }}" alt="" class="img-thumbnail" style="width: 35px;">
                                <span class="app-brand-text h3 mb-0 fw-bold">دنیز</span>
                            </div>
                        </div>
                        <div class="text-start">
                            <h4>صورتحساب : {{ $courseOrder->id }}</h4>
                            <div class="mb-2 lh-1-85">
                                <span class="me-1">تاریخ صدور:</span>
                                <span class="fw-semibold">{{ verta($courseOrder->created_at)->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="bs-stepper-content border-top">
                    <div id="checkout-cart" class="content mb-3">
                        <div class="row">
                            <div class="col-xl-8 mb-3 mb-xl-0">
                                <ul class="list-group mb-3">
                                    <li class="list-group-item p-4">
                                        <div class="d-flex gap-3">
                                            <div class="flex-grow-1">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="fw-normal mb-2 me-3 text-md-center">
                                                            <a class="text-body">دوره</a>
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="text-md-center">
                                                            <div class="my-2">
                                                                <span class="">شهریه </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @foreach ($courseOrder->courseOrderItems as $item)
                                        <li class="list-group-item p-4">
                                            <div class="d-flex gap-3">
                                                <div class="flex-grow-1">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <h6 class="fw-normal mb-2 me-3 text-md-center">
                                                                <a class="text-body">{{ $item->course->title }}</a>
                                                            </h6>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-md-center">
                                                                <div class="my-2">
                                                                    <span class="text-primary"> <span>{{ number_format($item->final_amount) }}</span> تومان</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-xl-4">
                                <div class="border rounded p-3 mb-3">
                                    <h6 class="secondary-font">جزئیات قیمت</h6>
                                    <dl class="row mb-0">
                                        <dt class="col-6 fw-normal">مجموع سفارش</dt>
                                        <dd class="col-6 text-end"> <span>{{ number_format($courseOrder->total_amount) }}</span> تومان</dd>
                                    </dl>
                                    <hr class="mx-n3 mt-0">
                                    <dl class="row mb-2">
                                        <dt class="col-6">مبلغ قابل پرداخت</dt>
                                        <dd class="col-6 fw-semibold text-end mb-0"> <span>{{ number_format($courseOrder->total_amount) }}</span> تومان</dd>
                                    </dl>
                                    @if ($courseOrder->payment_status == 'paid')
                                        <img src="{{ asset('images/paid.png') }}" id="paid-image" alt="" class="img-thumbnail float-end">
                                        <style>
                                            #paid-image {
                                                position: relative;
                                                width: 155px;
                                            }
                                        </style>
                                    @endif
                                    @if (in_array($courseOrder->payment_status, ['pending', 'canceled']))
                                        <a href="{{ route('user.course-orders.pay', $courseOrder) }}" class="btn btn-info w-100">پرداخت</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection