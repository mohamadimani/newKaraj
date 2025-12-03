<div class="container-fluid flex-grow-1 container-p-y">
    @php
        use App\Enums\CourseRegister\StatusEnum;
        use App\Models\CourseRegister;
        use Illuminate\Support\Facades\Auth;
        use App\Constants\PermissionTitle;
    @endphp
    <div class="card pb-3">
        <div class="align-items-center card-header  justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('course_registers.page_title') }}</span>
            <a class="btn btn-info float-end" href="{{ route('course-registers.create') }}">{{ __('course_registers.create') }}</a>
            <button type="button" class="btn btn-primary float-end me-2" data-bs-toggle="modal" data-bs-target="#historyModal">
                نمایش تاریخچه تغییرات
            </button>
            <!-- History Modal -->
            @include('livewire.admin.course-registers.log-modal')
        </div>
        <div class="card-body row">
            @include('admin.layouts.alerts')
            @include('admin.layouts.filters', ['courseShow' => true])
        </div>
        <div class="d-flex align-items-center justify-content-end mb-3">
            @if (count($selectedStudents) > 0)
                <div class="alert alert-info m-2" style="padding-top: 6px; padding-bottom: 3px;">
                    <span>{{ ' کارآموزان انتخاب شده برای ارسال پیامک ' . count($selectedStudents) }}</span>
                </div>
            @endif
            <button class="btn btn-primary m-2 send-group-sms-button" data-bs-toggle="modal" data-bs-target="#sendGroupSmsModal" id="send-multi-sms"
                {{ count($selectedStudents) === 0 ? 'disabled' : '' }}>
                {{ __('clues.send_multi_sms') }}
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th>
                            @if (count($courseRegisters) > 0)
                                @if ($selectAllStudents == 'yes')
                                    <span class="btn btn-sm btn-warning" wire:click="$set('selectAllStudents','no')">عدم انتخاب همه</span>
                                @else
                                    <span class="btn btn-sm btn-primary" wire:click="$set('selectAllStudents','yes')">انتخاب همه</span>
                                @endif
                            @else
                                #
                            @endif
                        </th>
                        <th>{{ __('users.full_name') }}</th>
                        <th>{{ __('course_registers.course') }}</th>
                        <th>{{ __('course_registers.description') }}</th>
                        <th>مشاور</th>
                        <th>{{ __('public.status') }}</th>
                        <th> پرداختی <sub>(تومان)</sub></th>
                        <th> باقی مانده <sub>(تومان)</sub></th>
                        <th>کیف پول</th>
                        <th>تاریخ ثبت </th>
                        <th>تاریخ سرنخ </th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($courseRegisters as $courseRegister)
                        <tr class="text-center">
                            <td>
                                {{ calcIterationNumber($courseRegisters, $loop) }}
                                &nbsp;
                                @if (in_array($courseRegister->student->id, $selectedStudents))
                                    <span class="btn btn-sm btn-warning" wire:click="unsetSelectedStudentId({{ $courseRegister->student->id }})">عدم انتخاب</span>
                                @else
                                    <span class="btn btn-sm btn-primary" wire:click="setSelectedStudentId({{ $courseRegister->student->id }})">انتخاب</span>
                                @endif
                            </td>
                            <td><a href="{{ route('students.edit', [$courseRegister->student->id]) }}">{{ $courseRegister->student->user->fullName }}</a></td>
                            <td>
                                <a href="{{ route('courses.course-students', $courseRegister->course->id) }}">
                                    {{ $courseRegister->course->title }}
                                </a>
                            </td>
                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $courseRegister->description }}">
                                @if (mb_strlen($courseRegister->description) > 0)
                                    <span class="badge bg-label-info"><i class="fa fa-info "></i></span>
                                @else
                                    ---
                                @endif
                            </td>
                            <td><span class="badge bg-label-warning">{{ $courseRegister->secretary->user->full_name ?? '--' }}</span></td>
                            <td>
                                <span class="badge bg-label-{{ $courseRegister->status->getColor() }} me-1">
                                    {{ $courseRegister->status->getLabel() }}
                                </span>
                            </td>
                            <td class="text-{{ $courseRegister->paid_amount > 0 ? 'success' : 'danger' }}">{{ number_format($courseRegister->paid_amount) }}</td>
                            <td class="text-danger">
                                @php
                                    $remainingAmount = $courseRegister->debt();
                                @endphp
                                @if ($remainingAmount > 0)
                                    {{ number_format($remainingAmount) }}
                                @else
                                    <span class="badge bg-label-info">{{ __('payments.settled') }}</span>
                                @endif
                            </td>
                            <td> <span class="badge bg-label-success">{{ $courseRegister->student?->user ? number_format($courseRegister->student?->user?->wallet) : 0 }}</span></td>
                            <td>{{ georgianToJalali($courseRegister->created_at, true) }}</td>
                            <td>{!! georgianToJalali($courseRegister->student->user->clue->created_at, true) . ' <br><span class="bg-label-warning"> ' . $courseRegister->student->user->clue->createdBy() . '</span>' !!}
                            </td>
                            <td>
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu font-14">
                                    <span class="dropdown-item cursor-pointer add-payment-button" @if ($courseRegister->is_paid == 0 or $remainingAmount == 0) {{ 'disabled' }} @endif
                                        data-paymentable-id="{{ $courseRegister->id }}" data-course-id="{{ $courseRegister->course_id }}" data-remaining-amount="{{ $remainingAmount }}"
                                        data-bs-toggle="modal" data-bs-target="#addNewCCModal">
                                        <i class="bx bx-dollar me-1 text-info"></i> اضافه کردن پرداخت
                                    </span>
                                    <a href="{{ route('payments.index', ['user_id' => $courseRegister->student->user->id, 'back_url' => route('course-registers.index', [], false)]) }}"
                                        class="dropdown-item">
                                        <i class="tf-icons text-success fa-solid fa-money-bill me-1"></i> {{ __('payments.page_title') }}
                                    </a>
                                    @if (Auth::user()->hasPermissionTo(PermissionTitle::COURSE_REGISTER_CREATE_PRICE_CAN_CHANGE))
                                        <span class="dropdown-item cursor-pointer" wire:click='setCourseRegisterId({{ $courseRegister->id }})' data-bs-toggle="modal"
                                            data-bs-target="#changeCourseRegisterAmountModal"><i class="bx bx-edit me-1 text-primary"></i> تغییر مبلغ دوره
                                        </span>
                                    @endif
                                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REGISTER_TECHNICAL))
                                        <span class="dropdown-item cursor-pointer" data-bs-toggle="modal" data-bs-target="#technicalRegisterModal"
                                            wire:click="setTechnicalRegisterInfo({{ $courseRegister->id }})">
                                            <i class="bx bx-briefcase me-1 text-gray"></i> ثبت فنی حرفه ای
                                        </span>
                                    @endif
                                    @if (Auth::user()->hasPermissionTo(PermissionTitle::COURSE_REGISTER_CHANGE_FOR_STUDENT))
                                        <a href="{{ route('course-registers.edit', $courseRegister->id) }}" class="dropdown-item">
                                            <i class="bx bx-redo me-1 text-primary"></i> تغییر دوره
                                        </a>
                                    @endif
                                    @if (Auth::user()->hasPermissionTo(PermissionTitle::COURSE_REGISTER_TO_RESERVE))
                                        <span class="dropdown-item cursor-pointer" data-bs-toggle="modal" data-bs-target="#reserveCourseRegisterModal"
                                            onclick="setReserveCourseRegisterId({{ $courseRegister->id }})">
                                            <i class="bx bx-transfer me-1 text-warning"></i> {{ __('course_registers.convert_to_reserve') }}
                                        </span>
                                    @endif
                                    @if (Auth::user()->hasPermissionTo(PermissionTitle::CANCEL_COURSE_REGISTER))
                                        <span class="dropdown-item cursor-pointer" data-bs-toggle="modal" data-bs-target="#cancelCourseRegisterModal{{ $courseRegister->id }}"
                                            data-course-register-id="{{ $courseRegister->id }}">
                                            <i class="bx bx-x-circle me-1 text-danger"></i>ثبت انصراف
                                        </span>
                                    @endif
                                    <span class="dropdown-item cursor-pointer" wire:click='setCourseRegisterId({{ $courseRegister->id }})' data-bs-toggle="modal"
                                        data-bs-target="#practicalExamNumberModal"><i class="fa fa-list-1-2 me-2 text-info"></i>ثبت نمره عملی
                                    </span>
                                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REFUND_PAYMENT))
                                        <span class="dropdown-item cursor-pointer" data-bs-toggle="modal" data-bs-target="#refundAmountModal"
                                            data-course-register-id="{{ $courseRegister->id }}" wire:click='setCourseRegisterId({{ $courseRegister->id }})' >
                                            <i class="bx bx-refresh me-1 text-primary"></i>عودت وجه
                                        </span>
                                    @endif
                                    @if ($courseRegister->student->user->id !== Auth::user()->id and isAdminNumber())
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="{{ route('auth.login-by-user-id', [$courseRegister->student->user->id]) }}"><i class="bx bx-log-in me-1"></i>
                                            ورود با دسترسی کاربر</a>
                                    @endif
                                </div>
                                <!-- Modal for Cancel Course Register -->
                                <div class="modal fade" id="cancelCourseRegisterModal{{ $courseRegister->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label> توضیحات انصراف</label>
                                                <textarea wire:model="cancel_description" class="form-control" placeholder="توضیحات انصراف"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                                                <button type="button" class="btn btn-info" wire:click="cancelCourseRegister('{{ $courseRegister->id }}')" data-bs-dismiss="modal">ثبت</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($courseRegisters) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $courseRegisters->links() }}</span>
        </div>
    </div>
    <!-- Modal for Cancel Course Register -->
    <div class="modal" id="reserveCourseRegisterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="font-bold mb-2"> توضیحات رزرو</label>{{ requireSign() }} :
                    <textarea wire:model="reserve_description" class="form-control" placeholder="توضیحات رزرو"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                    <button type="button" class="btn btn-info" id="" data-bs-dismiss="modal" wire:click='convertRegisterToReserve'>ثبت</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for practicalExamNumber  -->
    <div class="modal" id="practicalExamNumberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="exam_number">نمره آزمون عملی</label> {{ requireSign() }} :
                        <input wire:model="exam_number" type="text" id="exam_number" class="form-control text-start">
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="font-bold mb-2">توضیحات</label> :
                        <textarea wire:model="exam_description" class="form-control" placeholder="توضیحات"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                    <button type="button" class="btn btn-info" id="" data-bs-dismiss="modal" wire:click='setPracticalExamNumber()'>ثبت</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for refund amount  -->
    <div class="modal" id="refundAmountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="refund_amount">مبلغ عودت</label> {{ requireSign() }} :
                        <input wire:model="refund_amount" type="text" id="refund_amount" class="form-control text-start">
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="font-bold mb-2">توضیحات</label> :
                        <textarea wire:model="refund_description" class="form-control" placeholder="توضیحات"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                    <button class="btn btn-info" data-bs-dismiss="modal" wire:click='setRefundAmount()'>ثبت</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Change Course Register Amount -->
    <div class="modal fade" id="changeCourseRegisterAmountModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تغییر مبلغ دوره</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">مبلغ جدید دوره</label>
                        <input type="text" class="form-control" wire:model="amount" id="persian_amount" placeholder="مبلغ جدید را وارد کنید">
                        <small class="text-muted" id="persian_amount_text"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">توضیحات تغییر مبلغ</label>
                        <textarea class="form-control" wire:model="amount_description" placeholder="توضیحات تغییر مبلغ را وارد کنید"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                    <button type="button" class="btn btn-primary" wire:click="changeCourseRegisterAmount()" data-bs-dismiss="modal">ثبت تغییرات</button>
                </div>
            </div>
        </div>
    </div>
    @if ($courseRegisterId and $showModal)
        <!-- Modal for Technical Register -->
        <div class="modal d-block" id="technicalRegisterModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ثبت فنی حرفه ای</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">مبلغ پرداختی</label>{{ requireSign() }}
                            <input type="number" class="form-control" wire:model.live="technicalAmount">
                        </div>
                        @if ($technicalAmount > 0)
                            <div class="mb-3">
                                <label class="form-label">روش پرداخت</label>{{ requireSign() }}
                                <select wire:model="technicalPaymentMethodId" id="payment-method" class="select2 form-select" data-allow-clear="true"
                                    data-placeholder="{{ __('course_registers.select_payment_method') }}">
                                    <option value="">---</option>
                                    @foreach ($paymentMethods as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">تاریخ پرداخت</label>{{ requireSign() }}
                                <input data-jdp wire:model="technicalPayDate" type="text" id="pay-date" class="form-control " placeholder="تاریخ پرداخت" value="{{ old('pay_date') }}">
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="technicalPaidImage">تصویر فیش پرداخت </label> {{ requireSign() }}
                                <input wire:model="technicalPaidImage" type="file" id="technicalPaidImage" class="form-control" required>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">توضیحات</label>{{ requireSign() }}
                            <textarea class="form-control" wire:model="technicalAmountDescription"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="$set('showModal',null)">لغو</button>
                        <button type="button" class="btn btn-primary" wire:click="registerTechnical({{ $courseRegisterId }})" data-bs-dismiss="modal">ثبت</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="addNewCCModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4 mt-0 mt-md-n2">
                        <h3 class="secondary-font">اضافه کردن پرداخت</h3>
                    </div>
                    <form id="addNewCCForm" class="row g-3" action="{{ route('payments.store') }}" method="POST" wire:ignore enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 pt-3" wire:ignore>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="course">{{ __('course_registers.course') }}</label>
                                <select name="course_id" id="payment-course-id" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_registers.select_course') }}">
                                    <option value="">---</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="remaining-amount">{{ __('payments.remaining_amount') }}</label>
                                <input name="remaining_amount" value="" type="text" id="remaining-amount" class="form-control text-start">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="paid-amount">{{ __('course_registers.paid_amount') }}</label>
                                <input name="paid_amount" value="{{ old('paid_amount') }}" type="text" id="paid-amount" class="form-control text-start"
                                    placeholder="{{ __('course_registers.paid_amount_placeholder') }}">
                                <small class="text-muted" id="persian-number"></small>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="pay-date">{{ __('course_registers.pay_date') }}</label>
                                <input data-jdp name="pay_date" type="text" id="pay-date" class="form-control " placeholder="تاریخ پرداخت" value="{{ old('pay_date') }}">
                                @include('admin.layouts.jdp', ['time' => true])
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="payment-method">{{ __('course_registers.payment_method') }}</label>
                                <select name="payment_method_id" id="payment-method" class="select2 form-select" data-allow-clear="true"
                                    data-placeholder="{{ __('course_registers.select_payment_method') }}">
                                    <option value="">---</option>
                                    @foreach ($paymentMethods as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="payment-description">{{ __('course_registers.payment_description') }}</label>
                                <input name="payment_description" value="{{ old('payment_description') }}" type="text" id="payment-description" class="form-control text-start"
                                    placeholder="{{ __('course_registers.payment_description_placeholder') }}">
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="paid_image">تصویر فیش پرداخت </label> {{ requireSign() }}
                                <input name="paid_image" type="file" id="paid_image" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <input type="hidden" name="paymentable_id" id="paymentable_id" value="">
                            <input type="hidden" name="paymentable_type" value="{{ CourseRegister::class }}">
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                {{ __('public.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('public.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('components.modals.send-gruop-sms-modal')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var remainingAmount;
            $('#course-id').on('select2:select', function(e) {
                const price = e.params.data.element.dataset.price;
                $('#tuition-fee').val(new Intl.NumberFormat().format(price));
                $('#tuition-fee').prop('disabled', true);
            });
            $('.add-payment-button').on('click', function() {
                $('#paymentable_id').val($(this).data('paymentable-id'));
                $('#payment-course-id option[value="' + $(this).data('course-id') + '"]').prop('selected', true);
                $('#select2-payment-course-id-container').text($('#payment-course-id option:selected').text());
                $('#payment-course-id').prop('disabled', true);
                remainingAmount = $(this).data('remaining-amount');
                $('#remaining-amount').val(new Intl.NumberFormat().format($(this).data('remaining-amount')));
                $('#remaining-amount').prop('disabled', true);
            });
            $('#paid-amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    if (this.value > remainingAmount) {
                        this.value = remainingAmount;
                    }

                    this.value = new Intl.NumberFormat().format(this.value);
                    const numericValue = parseInt(this.value.replace(/,/g, ''));
                    const persianText = numberToPersianText(numericValue);
                    $('#persian-number').text(persianText + ' تومان');
                } else {
                    $('#persian-number').text('');
                }
            });
            $('#persian_amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    this.value = new Intl.NumberFormat().format(this.value);
                    const numericValue = parseInt(this.value.replace(/,/g, ''));
                    const persianText = numberToPersianText(numericValue);
                    $('#persian_amount_text').text(persianText + ' تومان');
                } else {
                    $('#persian_amount_text').text('');
                }
            });
        });

        function setReserveCourseRegisterId(reserveCourseRegisterId) {
            @this.setReserveCourseRegisterId(reserveCourseRegisterId);
        }

        function setCourseRegisterId(courseRegisterId) {
            @this.setCourseRegisterId(courseRegisterId);
        }
    </script>
</div>
