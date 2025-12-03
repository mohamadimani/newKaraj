<div class="container-fluid flex-grow-1 container-p-y">
    <div class="col-12 mb-md-0 mb-4">
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
                        <div class="d-flex align-items-center svg-illustration mb-3 gap-2">
                            <img src="{{ asset('admin-panel/assets/img/logo/logo.jfif') }}" alt="" class="img-thumbnail" style="width: 35px;">
                            <span class="app-brand-text h3 mb-0 fw-bold">دنیز</span>
                        </div>
                    </div>
                    <div class="text-start">
                        <h4>صورتحساب : {{ $order->id }}</h4>
                        <div class="mb-2 lh-1-85">
                            <span class="me-1">تاریخ صدور:</span>
                            <span class="fw-semibold">{{ verta($order->created_at)->format('Y/m/d') }}</span>
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
                                @foreach ($order->orderItems as $item)
                                    <li class="list-group-item p-4">
                                        <div class="d-flex gap-3">
                                            <div class="flex-grow-1">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="fw-normal mb-2 me-3 text-md-center">
                                                            <a class="text-body">{{ $item->onlineCourse->name }}</a>
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="text-md-center">
                                                            <div class="my-2">
                                                                <span class="text-primary"> <span>{{ number_format($item->amount) }}</span> تومان</span>
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
                                <h5 class="secondary-font mb-5">جزئیات مالی</h5>

                                @if ($order->payment_status == 'pending')
                                    <div class="row g-3 mb-5">
                                        <h6 class="card-title m-0  font-15">کد تخفیف : </h6>
                                        <div class="col-8 col-xxl-8 col-xl-12 mt-0">
                                            <input type="text" wire:model="discount_code" class="form-control" placeholder="کد تخفیف را وارد کنید" aria-label="Enter Promo Code">
                                            @error('discount_code')
                                                <span class="text-danger font-12">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-4 col-xxl-4 col-xl-12  mt-0">
                                            <div class="d-grid">
                                                <button type="submit" wire:click="applyDiscountCode" class="btn btn-label-primary">اعمال</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <h6 class="card-title m-0  font-15">کد معرف :
                                            @if ($order->reference_code)
                                                <i class="fa fa-check-circle text-success"></i>
                                            @endif
                                        </h6>
                                        <div class="col-8 col-xxl-8 col-xl-12 mt-0">
                                            <input type="text" wire:model="reference_code" class="form-control" placeholder="کد معرف را وارد کنید" aria-label="Enter Promo Code">
                                            @error('reference_code')
                                                <span class="text-danger font-12">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-4 col-xxl-4 col-xl-12  mt-0">
                                            <div class="d-grid">
                                                <button type="submit" wire:click="applyReferenceCode" class="btn btn-label-primary">اعمال</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                <dl class="row mb-0">
                                    <dt class="col-6 fw-normal">مجموع سفارش</dt>
                                    <dd class="col-6 text-end"> <span>{{ number_format($order->total_amount) }}</span> تومان</dd>
                                </dl>
                                <dl class="row mb-0">
                                    <dt class="col-6 fw-normal">تخفیف</dt>
                                    <dd class="col-6 text-end"> <span>{{ number_format($order->discount_amount) }}</span> تومان</dd>
                                </dl>
                                <dl class="row mb-0">
                                    <dt class="col-6 fw-normal">پرداخت شده</dt>
                                    @php
                                        $paidAmountSum = $order->onlinePayments()->where('pay_confirm', true)->sum('paid_amount');
                                    @endphp
                                    <dd class="col-6 text-end"> <span>{{ number_format($paidAmountSum) }}</span> تومان</dd>
                                </dl>
                                <hr class="mx-n3 mt-0">
                                <dl class="row mb-2">
                                    <dt class="col-6">مبلغ قابل پرداخت</dt>
                                    <dd class="col-6 fw-semibold text-end mb-0"> <span>{{ number_format($order->final_amount - $paidAmountSum) }}</span> تومان</dd>
                                </dl>
                                @if ($order->payment_status == 'paid')
                                    <img src="{{ asset('images/paid.png') }}" id="paid-image" alt="" class="img-thumbnail float-end">
                                    <style>
                                        #paid-image {
                                            position: relative;
                                            width: 155px;
                                        }
                                    </style>
                                @endif
                                @if (in_array($order->payment_status, ['pending']))
                                    <a href="{{ route('user.orders.pay', $order) }}" class="btn btn-success w-100 mb-2">پرداخت درگاه</a>
                                    <a wire:click="payByWallet()" class="btn btn-info w-100 text-white">پرداخت با کیف پول (موجودی : <span>{{ number_format($order->user->wallet) }}</span>
                                        )</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
