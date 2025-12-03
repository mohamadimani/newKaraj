<div class="container-fluid flex-grow-1 container-p-y">
    @php
        use App\Enums\Payment\StatusEnum;
        use App\Models\User;
        use App\Models\CourseRegister;
        use App\Models\CourseReserve;
        use App\Models\Order;
        use App\Constants\PermissionTitle;
        use App\Models\PaymentMethod;
        use App\Models\Technical;
    @endphp

    <style>
        /* payment image style */
        .icon-container {
            display: inline-block;
            cursor: pointer;
            position: relative;
        }

        .imageOverlay {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 400px;
            height: auto;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            overflow: auto;
        }

        .imageOverlay img {
            width: 255px;
            height: auto;
            display: block;
            margin: 10px 1% 0 0;
            opacity: 1;
            /* padding: 3px; */
            border: 4px solid white;
            border-radius: 5px;
            box-shadow: 1px 1px 5px silver;
        }

        #zoomImage:hover {
            width: 380px;
            height: auto;
            transition: transform 0.2s, transform-origin 0.2s;
        }

        .closeImage {
            position: absolute;
            top: 2px;
            right: 0;
            font-size: 15px;
        }

        .break-all{
            white-space: break-spaces !important;
            line-height: 16px !important;
        }

        .w-250{
            min-width: 250px !important;
        }
    </style>
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            @if ($queryUserId)
                @php
                    $user = User::find($queryUserId);
                @endphp
                <span class="font-20">{!! __('payments.payments_of_user', ['user' => '<b class="text-primary">' . $user->fullName . '</b>']) !!}</span>
            @elseif ($queryCourseRegisterId)
                @php
                    $courseRegister = CourseRegister::find($queryCourseRegisterId);
                @endphp
                <span class="font-20">{!! __('payments.payments_of_user_on_course', [
                    'user' => '<b class="text-primary">' . $courseRegister->student->user->fullName . '</b>',
                    'course' => '<b class="text-primary">' . $courseRegister->course->title . '</b>',
                ]) !!}</span>
            @elseif ($queryCourseReserveId)
                @php
                    $courseReserve = CourseReserve::find($queryCourseReserveId);
                @endphp
                <span class="font-20">{!! __('payments.payments_of_user_on_reserve', [
                    'user' => '<b class="text-primary">' . $courseReserve->clue->user->fullName . '</b>',
                    'reserve' => '<b class="text-primary">' . $courseReserve->profession->title . '</b>',
                ]) !!}</span>
            @else
                <span class="font-20 fw-bold heading-color">{{ __('payments.page_title') }}</span>
            @endif
            @if ($backUrl)
                <div class="card-header-actions">
                    <a href="{{ $backUrl }}" class="btn btn-label-primary mb-3">
                        <i class="tf-icons fa-solid fa-arrow-right-from-bracket"></i>
                        {{ __('public.back') }}
                    </a>
                </div>
            @endif
        </div>
        <div class="card-body border-bottom">
            <div class="row">
                <div class="col-md-2">
                    <label class="form-label" for="search">{{ __('public.search') }}</label> :
                    <input type="text" class="form-control" wire:model.live="search" placeholder="{{ __('payments.search_all') }}">
                </div>
                <div class="col-md-2" wire:ignore>
                    <label class="form-label" for="selectedSecretaryId">ثبت کننده</label> :
                    <select class="form-control select2 " id="selectedSecretaryId" wire:model.live="selectedSecretaryId" onchange="myFunction()">
                        <option value="">{{ __('public.all') }}</option>
                        @foreach ($secretaries as $secretary)
                            <option value="{{ $secretary->id }}">{{ $secretary->user->fullName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label" for="paymentStatus">وضعیت</label> :
                    <select class="form-select" wire:model.live="paymentStatus">
                        <option value="">{{ __('public.all') }}</option>
                        @foreach (StatusEnum::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->getLabel() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label" for="paymentMethod">روش </label> :
                    <select class="form-select" wire:model.live="paymentMethod">
                        <option value="0">{{ __('public.all') }}</option>
                        @foreach ($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="paymentType">{{ __('payments.payment_type') }}</label> :
                    <select class="form-select" wire:model.live="paymentType" id="paymentType">
                        <option value="0">{{ __('public.all') }}</option>
                        <option value="{{ CourseRegister::class }}">{{ __('payments.type_course_register') }}</option>
                        <option value="{{ CourseReserve::class }}">{{ __('payments.type_course_reserve') }}</option>
                        <option value="{{ Order::class }}">سفارش دوره آنلاین </option>
                        <option value="{{ Technical::class }}">فنی حرفه ای </option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label" for="startDate">از</label> :
                    <input data-jdp type="text" class="form-control " wire:model.live="startDate" placeholder="تاریخ">
                    @include('admin.layouts.jdp', ['time' => false])
                </div>
                <div class="col-md-1">
                    <label class="form-label" for="endDate">تا</label> :
                    <input data-jdp type="text" class="form-control " wire:model.live="endDate" placeholder="تاریخ">
                </div>
                @if (Auth::user()->hasPermissionTo(PermissionTitle::VERIFY_PAYMENT))
                    <div class="col-md-2 d-flex align-items-end justify-content-end ">
                        <button class="btn btn-primary" wire:click="bulkVerifyPayment" {{ count($selectedPayments) === 0 ? 'disabled' : '' }}>
                            تایید انتخابی ها ({{ count($selectedPayments) }})
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>رسید</th>
                        <th>{{ __('payments.user') }}</th>
                        <th>{{ __('payments.course') }}</th>
                        <th>{{ __('payments.payment_type') }}</th>
                        <th>{{ __('payments.pay_date') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('payments.registrant') }}</th>
                        <th>{{ __('payments.created_at') }}</th>
                        <th>{{ __('payments.description') }}</th>
                        <th>مبلغ پرداخت <sub>(تومان)</sub></th>
                        <th>مانده<sub>(تومان)</sub></th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($payments as $payment)
                        <tr class="text-center">
                            <td>
                                {{ calcIterationNumber($payments, $loop) }}
                                @if (Auth::user()->hasPermissionTo(PermissionTitle::VERIFY_PAYMENT) and $payment->status === StatusEnum::PENDING)
                                    @if (in_array($payment->id, $selectedPayments))
                                        <span class="btn btn-sm btn-warning" wire:click="unsetSelectedPaymentId({{ $payment->id }})">عدم انتخاب</span>
                                    @else
                                        <span class="btn btn-sm btn-primary" wire:click="setSelectedPaymentId({{ $payment->id }})">انتخاب</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="icon-container" onclick="showImageOverlay(<?= $payment->id ?>)">
                                    <i class="fa-solid fa-image text-info  cursor-pointer hover_show_image font-18"></i>
                                </div>
                                <div class="overlay imageOverlay" id="imageOverlay{{ $payment->id }}" onclick="hideImageOverlay()">
                                    <img src="{{ GetImage('payments/bill/' . $payment?->paymentImage?->title) }}" alt="تصویر پرداخت" id="zoomImage" />
                                    <i class="closeImage btn btn-danger btn-sm bx bx-x">{{ $payment->user->fullName }}</i>
                                </div>
                            </td>
                            <td>{{ $payment->user->fullName }}</td>
                            <td><span style="line-height: 20px;">{!! $payment->paymentableTitle !!} </span> </td>
                            <td>{{ $payment->paymentableTypes }}</td>
                            <td> {{ $payment->pay_date }} <br> <span class="badge bg-label-primary">{{ $payment->paymentMethod->title }} </span> </td>
                            <td> <span class="badge bg-label-{{ $payment->status->getColor() }} me-1"> {{ $payment->status->getLabel() }} </span> </td>
                            <td> <span class="badge bg-label-warning"> {{ $payment->createdBy->fullName }} </span></td>
                            <td>{{ georgianToJalali($payment->created_at, true) }}</td>
                               <td>
                                <div class="w-250">
                                @if ($payment->description)
                                    <span class="badge bg-label-info break-all "> {{ $payment->description }}</span>
                                @endif
                                @if ($payment->reject_description)
                                    @if ($payment->description)
                                        <br>
                                    @endif
                                    <span class="badge bg-label-danger break-all "> {{ $payment->reject_description }}</span>
                                @endif
                                </div>
                            </td>
                            <td>
                                {{ number_format($payment->paid_amount) }}
                            </td>
                            <td>
                                @if ($payment->paymentable instanceof CourseRegister or $payment->paymentable instanceof Order)
                                    @php
                                        $debt = $payment->paymentable->debt();
                                    @endphp
                                    <span class="badge bg-label-{{ $debt > 0 ? 'danger' : 'success' }}">
                                        {{ number_format($debt) }}
                                    </span>
                                @else
                                    <span class="badge bg-label-danger">
                                        ---
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($payment->status === StatusEnum::PENDING)
                                    <div class="d-flex gap-2">
                                        @if (Auth::user()->hasPermissionTo(PermissionTitle::VERIFY_PAYMENT))
                                            <button type="button" class="btn rounded-pill btn-icon btn-label-primary verify-payment-button" wire:click="verifyPayment({{ $payment->id }})">
                                                <span class="tf-icons bx bx-check" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('payments.verify') }}"></span>
                                            </button>
                                        @endif
                                        @if (Auth::user()->hasPermissionTo(PermissionTitle::REJECT_PAYMENT))
                                            <button type="button" class="btn rounded-pill btn-icon btn-label-danger reject-payment-button" data-bs-target="#rejectPaymentModal"
                                                data-bs-toggle="modal" wire:click="$set('paymentId', {{ $payment->id }})">
                                                <span class="tf-icons bx bx-x" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('payments.reject') }}"></span>
                                            </button>
                                        @endif
                                    @elseif ($payment->status === StatusEnum::VERIFIED and Auth::user()->hasPermissionTo(PermissionTitle::REDO_VERIFY_PAYMENT) and $payment->is_wallet_pay == false)
                                        <button type="button" class="btn rounded-pill btn-icon btn-label-warning redo-verify-payment-button" wire:click="redoVerifyPayment({{ $payment->id }})">
                                            <span class="tf-icons bx bx-redo" data-bs-toggle="tooltip" data-bs-placement="top" title="لغو تایید"></span>
                                        </button>
                                @endif
                                @if ($payment->status === StatusEnum::PENDING and Auth::user()->hasPermissionTo(PermissionTitle::CHANGE_PAYMENT_AMOUNT))
                                    <button type="button" class="btn rounded-pill btn-icon btn-label-info" data-bs-toggle="modal" data-bs-target="#editPaymentDetailsModal"
                                        wire:click="setEditPaymentData({{ $payment->id }})">
                                        <span class="tf-icons bx bx-edit" data-bs-toggle="tooltip" data-bs-placement="top" title="ویرایش پرداخت"></span>
                                    </button>
                                @endif
        </div>
        <!-- Edit Payment Details Modal -->
        <div class="modal fade" id="editPaymentDetailsModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title">ویرایش پرداخت</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">مبلغ پرداخت</label>
                            <input type="text" id="editPaidAmount" class="form-control" wire:model="editPaidAmount">
                            <small class="text-muted" id="edit-persian-number"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تاریخ پرداخت</label>
                            <input type="text" class="form-control" wire:model="editPayDate">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">روش پرداخت</label>
                            <select class="form-select" wire:model="editPaymentMethod">
                                <option value="">انتخاب کنید</option>
                                @foreach ($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="button" class="btn btn-primary" wire:click="updatePayment({{ $payment->id }})" data-bs-dismiss="modal">ذخیره تغییرات</button>
                    </div>
                </div>
            </div>
        </div>
        </td>
        </tr>
        @endforeach
        </tbody>
        </table>
        @if (count($payments) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
        @endif
    </div>
    <div class="p-3">
        <span class="d-block mt-3">{{ $payments->links() }}</span>
    </div>
</div>
<div class="modal  " id="rejectPaymentModal" tabindex="0" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4 mt-0 mt-md-n2">
                    <h3 class="mb-4 secondary-font">{{ __('payments.reject_payment') }}</h3>
                </div>
                <div class="col-12">
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="reject-reason">{{ __('payments.reject_reason') }}</label>
                        <select wire:model="rejectReason" id="reject-reason" class="form-select" required>
                            <option value="">---</option>
                            @foreach ([__('payments.reasons.payment_already_verified'), __('payments.reasons.payment_already_rejected'), __('payments.reasons.not_enough_amount'), __('payments.reasons.not_enough_information'), __('payments.reasons.other')] as $rejectReason => $rejectReasonLabel)
                                <option value="{{ $rejectReasonLabel }}">{{ $rejectReasonLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="form-label" for="rejectPaymentDescription">{{ __('payments.reject_description') }}</label>
                    <div class="input-group input-group-merge">
                        <textarea rows="4" type="text" id="rejectPaymentDescription" wire:model="rejectDescription" class="form-control text-start" placeholder="{{ __('payments.reject_description_text') }}"></textarea>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center mt-4">
                    <button class="btn btn-success me-sm-3 me-1" wire:click="rejectPayment()" data-bs-dismiss="modal">{{ __('payments.verify') }}</button>
                    <button class="btn btn-label-danger" data-bs-dismiss="modal" aria-label="Close">
                        {{ __('public.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .table> :not(caption)>*>* {
        padding: 0.625rem 0.5rem;
        background-color: var(--bs-table-bg);
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    }
</style>

<script>
    const editPaidAmount = document.getElementById('editPaidAmount');
    const editPersianTextElement = document.getElementById('edit-persian-number');
    editPaidAmount.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
        if (this.value) {
            this.value = new Intl.NumberFormat().format(this.value);
            const numericValue = parseInt(this.value.replace(/,/g, ''));
            const persianText = numberToPersianText(numericValue);
            editPersianTextElement.textContent = persianText + ' تومان';
        } else {
            editPersianTextElement.textContent = '';
        }
    });

    function myFunction() {
        const value = $('select#selectedSecretaryId').val();
        @this.setSelectedSecretaryId(value)
    }
    myFunction()

    function showImageOverlay(id) {
        $('.imageOverlay').hide()
        $('#imageOverlay' + id).show()
    }

    function hideImageOverlay() {
        $('.imageOverlay').hide()
    }
</script>
</div>
