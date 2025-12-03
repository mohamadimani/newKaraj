<div class="container-fluid flex-grow-1 container-p-y">
    @php
        use App\Enums\Payment\StatusEnum;
        use App\Models\CourseRegister;
        use App\Models\CourseReserve;
        use App\Models\Order;
        use App\Models\Technical;
    @endphp

    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">گزارش مالی</span>
        </div>
        <div class="row ">
            <div class="col-md-12 ">

                <div class="card-body ">
                    <div class="card p-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="font-13 mb-1" for="startDate">از تاریخ</label>:
                                <input data-jdp type="text" wire:model.live="startDate" id="startDate" class="form-control" placeholder="از تاریخ">
                            </div>
                            <div class="col-md-2">
                                <label class="font-13 mb-1" for="endDate">تا تاریخ</label>:
                                <input data-jdp type="text" wire:model.live="endDate" id="endDate" class="form-control" placeholder="تا تاریخ">
                                @include('admin.layouts.jdp')
                            </div>
                            <div class="col-md-2" wire:ignore>
                                <label class="font-13 mb-1" for="secretaryId">وضعیت</label>:
                                <select class="form-select" wire:model.live="paymentStatus">
                                    <option value="">{{ __('public.all') }}</option>
                                    @foreach (StatusEnum::cases() as $status)
                                        <option value="{{ $status->value }}">{{ $status->getLabel() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="align-items-center card-header d-flex justify-content-between">
                        <span class="font-15 fw-bold heading-color">مجموع پرداخت ها</span>
                    </div>
                    <div class="mb-4 col-4">
                        <div class="card py-2">
                            <div class="row m-2">
                                <div class=" col-12 ">
                                    <span class="text-primary d-inline-block">کل</span>
                                    @php
                                        $paymentsSumClone = clone $payments;
                                        $paidAmount = $paymentsSumClone->sum('paid_amount');
                                    @endphp
                                    <span class="float-right  " id="MCounts0">{{ number_format($paidAmount) }} تومان </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 col-4">
                        <div class="card py-2">
                            <div class="row m-2">
                                <div class=" col-12 ">
                                    <span class="text-primary d-inline-block">{{ __('payments.type_course_register') }}</span>
                                    @php
                                        $paymentsSumClone = clone $payments;
                                        $paidAmount = $paymentsSumClone->where('paymentable_type', CourseRegister::class)->sum('paid_amount');
                                    @endphp
                                    <span class="float-right  " id="MCounts0">{{ number_format($paidAmount) }} تومان </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-4">
                        <div class="card py-2">
                            <div class="row m-2">
                                <div class=" col-12 ">
                                    <span class="text-primary d-inline-block">{{ __('payments.type_course_reserve') }}</span>
                                    @php
                                        $paymentsSumClone = clone $payments;
                                        $paidAmount = $paymentsSumClone->where('paymentable_type', CourseReserve::class)->sum('paid_amount');
                                    @endphp
                                    <span class="float-right  " id="MCounts0">{{ number_format($paidAmount) }} تومان </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-4">
                        <div class="card py-2">
                            <div class="row m-2">
                                <div class=" col-12 ">
                                    <span class="text-primary d-inline-block">سفارش دوره آنلاین</span>
                                    @php
                                        $paymentsSumClone = clone $payments;
                                        $paidAmount = $paymentsSumClone->where('paymentable_type', Order::class)->sum('paid_amount');
                                    @endphp
                                    <span class="float-right  " id="MCounts0">{{ number_format($paidAmount) }} تومان </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-4">
                        <div class="card py-2">
                            <div class="row m-2">
                                <div class=" col-12 ">
                                    <span class="text-primary d-inline-block">فنی حرفه ای</span>
                                    @php
                                        $paymentsSumClone = clone $payments;
                                        $paidAmount = $paymentsSumClone->where('paymentable_type', Technical::class)->sum('paid_amount');
                                    @endphp
                                    <span class="float-right  " id="MCounts0">{{ number_format($paidAmount) }} تومان </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="align-items-center card-header d-flex justify-content-between">
                        <span class="font-15 fw-bold heading-color">روش های پرداخت</span>
                    </div>
                    @foreach ($payMethods as $payMethod)
                        <div class="mb-4 col-4">
                            <div class="card py-2">
                                <div class="row m-2">
                                    <div class=" col-12 ">
                                        <span class="text-primary d-inline-block">{{ $payMethod->title }}</span>
                                        @php
                                            $paymentsClone = clone $payments;
                                            $paidAmount = $paymentsClone->where('payment_method_id', $payMethod->id)->sum('paid_amount');
                                        @endphp
                                        <span class="float-right  " id="MCounts0">{{ number_format($paidAmount) }} تومان </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
