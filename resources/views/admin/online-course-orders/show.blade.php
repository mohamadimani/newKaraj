@extends('admin.layouts.master')
@section('content')
    @php
        use App\Constants\PermissionTitle;
    @endphp
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="mb-2">
            <a href="{{ route('online-course-orders.index') }}" class="btn btn-primary">
                <i class="bx bx-arrow-back me-1"></i> بازگشت به لیست سفارشات
            </a>
        </div>
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    @include('admin.layouts.alerts')
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                        <div class="mb-xl-0 mb-4">
                            <div class="d-flex align-items-center svg-illustration mb-3 gap-2">
                                <img src="{{ asset('admin-panel/assets/img/logo/logo.jfif') }}" alt="" class="img-thumbnail" style="width: 35px;">
                                <span class="app-brand-text h3 mb-0 fw-bold">دنیز</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <h4>صورتحساب : {{ $order->id }}</h4>
                            <div class="mb-2 lh-1-85">
                                <span class="me-1">تاریخ صدور:</span>
                                <span class="fw-semibold">{{ verta($order->created_at)->format('Y/m/d') }}</span>
                            </div>
                            <div class="lh-1-85">
                                <span class="me-1">تاریخ سررسید:</span>
                                <span class="fw-semibold">{{ verta($order->created_at->addDays(10))->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="table-responsive">
                    <table class="table border-top m-0 mb-5">
                        <thead>
                            <tr class="text-center">
                                <th> دوره</th>
                                <th>شهریه</th>
                                @if (Auth::user()->hasPermissionTo(PermissionTitle::UPDATE_ORDER_ITEM_AMOUNT) and $order->payment_status == 'pending')
                                    <th>اصلاح قیمت</th>
                                    <th>حذف</th>
                                @endif
                                <th>کپی لایسنس</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr class="text-center">
                                    <td class="text-nowrap">{{ $item->onlineCourse->name }}</td>
                                    <td class="text-nowrap">{{ number_format($item->amount) }}</td>
                                    @if (Auth::user()->hasPermissionTo(PermissionTitle::UPDATE_ORDER_ITEM_AMOUNT) and $order->payment_status == 'pending')
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#changeAmountModal_{{ $item->id }}">
                                                تغییر
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="changeAmountModal_{{ $item->id }}" tabindex="-1" aria-labelledby="changeAmountModalLabel_{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="changeAmountModalLabel_{{ $item->id }}">تغییر شهریه</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="changeAmountForm_{{ $item->id }}" method="POST" action="{{ route('online-course-orders.update-amount', $item->id) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <div class="mb-3">
                                                                    <label for="newAmount_{{ $item->id }}" class="form-label">شهریه جدید</label>
                                                                    <input type="number" class="form-control" id="newAmount_{{ $item->id }}" name="new_amount" value="{{ $item->amount }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="description_{{ $item->id }}" class="form-label">توضیحات</label>
                                                                    <textarea class="form-control" id="description_{{ $item->id }}" name="description" required>{{ $item->change_amount_description }}</textarea>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                                            <button type="submit" form="changeAmountForm_{{ $item->id }}" class="btn btn-primary">ذخیره تغییرات</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('online-course-orders.delete-item', [$order->id, $item->id]) }}" class="btn btn-danger btn-sm">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </td>
                                    @endif
                                    <td>
                                        @if ($order->payment_status === 'paid')
                                            <span class="text-center d-none alert alert-info copy_license_key_{{ $item->id }}"><strong>کپی شد</strong></span>
                                            <button class="btn btn-success btn-sm license_key_{{ $item->id }}" onclick="copy('license_key_{{ $item->id }}')"
                                                data-lisence="{{ $item->license_key }}">
                                                <i class="bx bxs-copy"></i>
                                            </button>
                                        @else
                                            <span class="">تسویه نشده</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="align-top px-4 py-5">
                                    <p class="mb-2">
                                        <span class="me-1 fw-semibold">خریدار:</span>
                                        <span>{{ $order->user->fullName }}</span>
                                    </p>
                                    <span>با تشکر از خرید شما</span>
                                    @if ($order->payment_status == 'paid')
                                        <img src="{{ asset('images/paid.png') }}" id="paid-image" alt="" class="img-thumbnail float-end">
                                        <style>
                                            #paid-image {
                                                position: relative;
                                                top: 0;
                                                left: 0;
                                                margin-left: 100px;
                                                width: 155px;
                                            }
                                        </style>
                                    @endif
                                </td>
                                @if (Auth::user()->hasPermissionTo(PermissionTitle::UPDATE_ORDER_ITEM_AMOUNT) and $order->payment_status == 'pending')
                                    <td></td>
                                @endif
                                <td class="text-end text-nowrap px-4 py-5">
                                    <p class="mb-2">جمع :</p>
                                    <p class="mb-2">تخفیف:</p>
                                    <p class="mb-0">جمع کل:</p>
                                    <p class="mb-0">پرداخت شده:</p>
                                    <p class="mb-0">باقی مانده:</p>
                                    @if ($order->payment_status !== 'paid')
                                        <p class="mt-3">ایجاد پرداخت:</p>
                                    @endif
                                </td>
                                <td class="text-nowrap px-4 py-5 position-relative">
                                    <p class="fw-semibold mb-2">{{ number_format($order->total_amount) }} تومان</p>
                                    <p class="fw-semibold mb-2">{{ number_format($order->discount_amount) }} تومان</p>
                                    <p class="fw-semibold mb-0">{{ number_format($order->final_amount) }} تومان</p>
                                    <p class="fw-semibold mb-0">{{ number_format($totalPaidAmount) }} تومان</p>
                                    <p class="fw-semibold mb-0">{{ number_format($order->final_amount - $totalPaidAmount) }} تومان</p>
                                    @if ($order->payment_status !== 'paid' and $order->final_amount - $totalPaidAmount > 0)
                                        <button type="button" class="btn btn-success w-50 mt-3 " data-bs-toggle="modal" data-bs-target="#addPaymentModal">پرداخت</button>
                                    @endif
                                    @if ($order->payment_status !== 'paid' and $order->final_amount - $totalPaidAmount == 0)
                                        <a href="{{ route('online-course-orders.checkout', [$order->id]) }}" type="button" class="btn btn-success w-50 mt-3">تسویه سفارش</a>
                                    @endif

                                    <!-- Modal -->
                                    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addPaymentModalLabel">افزودن پرداخت جدید</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('online-course-orders.pay', [$order->id]) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="paid_amount" class="form-label">مبلغ پرداخت</label> {{ requireSign() }}
                                                            <input type="number" class="form-control" id="paid_amount" name="paid_amount" required>
                                                            <small class="text-muted" id="paid_amount_persian_text"></small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="payment_method_id" class="form-label">روش پرداخت</label>{{ requireSign() }}
                                                            <select class="form-select" id="payment_method_id" name="payment_method_id" required>
                                                                @foreach ($paymentMethods as $method)
                                                                    @if ($method->id == 16)
                                                                        <option value="{{ $method->id }}">{{ $method->title . ' (' . number_format($order->user->wallet) . ') ' }}</option>
                                                                    @else
                                                                        <option value="{{ $method->id }}">{{ $method->title }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="pay-date">{{ __('course_registers.pay_date') }}</label>{{ requireSign() }}
                                                            <input name="pay_date" type="text" id="pay-date" class="form-control dob-picker"
                                                                placeholder="{{ __('course_registers.pay_date_placeholder') }}" value="{{ old('pay_date') }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="paid_image">تصویر فیش پرداخت </label> {{ requireSign() }}
                                                            <input name="paid_image" type="file" id="paid_image" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="payment_description">توضیحات</label> {{ requireSign() }}
                                                            <input name="payment_description" type="text" id="payment_description" class="form-control" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">ثبت پرداخت</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paidAmountInput = document.getElementById('paid_amount');
            const persianTextElement = document.getElementById('paid_amount_persian_text');
            paidAmountInput.addEventListener('input', function() {
                const amountNumericValue = parseInt(this.value.replace(/,/g, ''));
                if (!isNaN(amountNumericValue)) {
                    persianTextElement.textContent = numberToPersianText(amountNumericValue) + ' تومان';
                } else {
                    persianTextElement.textContent = '';
                }
            });
        });

        function copy(item) {
            var lisence = $('.' + item).attr('data-lisence');
            navigator.clipboard.writeText(lisence);
            $('span.copy_' + item).removeClass('d-none');
            $('span.copy_' + item).fadeIn(100);
            setTimeout(() => {
                $('span.copy_' + item).fadeOut(300);
            }, 1500);

        }
    </script>
@endsection
