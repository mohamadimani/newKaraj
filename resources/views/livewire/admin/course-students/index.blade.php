@php
    use App\Models\Course;
    use App\Models\CourseRegister;
    use Illuminate\Support\Facades\Auth;
    use App\Constants\PermissionTitle;
@endphp
<div class="container-fluid flex-grow-1 container-p-y">
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-15">{!! __('students.students_of_course', ['course' => '<b class="text-primary">' . $course->title . '</b>']) !!}</span>
            @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE_WITHDRAW) and !$course->teacher_withdraw_at)
                <span class="btn btn-label-warning mb-3 btn-sm" wire:click='withdrawCourse()'>
                    <i class="tf-icons fa-solid me-1 fa-dollar"></i>
                    تسویه استاد
                </span>
            @endif
            @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE_WITHDRAW) and $course->teacher_withdraw_at)
                <span class="btn btn-label-success mb-3 btn-sm" >
                    <i class="tf-icons fa-solid me-1 fa-dollar"></i>
                    تسویه شده
                </span>
            @endif
            @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE_WITHDRAW_REDO) and $course->teacher_withdraw_at)
                <span class="btn btn-label-danger mb-3 btn-sm" wire:click='reDoWithdrawCourse()'>
                    <i class="tf-icons fa-solid me-1 fa-dollar"></i>
                    لغو تسویه استاد
                </span>
            @endif
            <div class="card-header-actions">
                <a href="{{ route('courses.index') }}" class="btn btn-label-primary mb-3 btn-sm">
                    <i class="tf-icons fa-solid fa-arrow-right-from-bracket"></i>
                    {{ __('public.back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <input type="text" class="form-control" wire:model.live="search" placeholder="{{ __('public.search') }}">
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
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="padding-right: 20px !important;">
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
                        <th>کارآموز</th>
                        <th>{{ __('users.mobile') }}</th>
                        <th>{{ __('clues.familiarity_ways') }}</th>
                        <th>{{ __('students.secretaries') }}</th>
                        <th>پرداختی ها <sub>(تومان)</sub> </th>
                        <th>بدهی ها <sub>(تومان)</sub> </th>
                        <th>دریافت پک</th>
                        <th>توضیحات</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($courseRegisters as $courseRegister)
                        <tr class="text-center">
                            <td style="padding-right: 20px !important;">
                                {{ $i++ }}
                                &nbsp;
                                @if (in_array($courseRegister->student->id, $selectedStudents))
                                    <span class="btn btn-sm btn-warning" wire:click="unsetSelectedStudentId({{ $courseRegister->student->id }})">عدم انتخاب</span>
                                @else
                                    <span class="btn btn-sm btn-primary" wire:click="setSelectedStudentId({{ $courseRegister->student->id }})">انتخاب</span>
                                @endif
                            </td>
                            <td><a href="{{ route('students.edit', $courseRegister->student->id) }}">{{ $courseRegister->student->user->fullName }}</a></td>
                            <td>{{ $courseRegister->student->user->mobile }}</td>
                            <td>
                                <span class="badge bg-label-secondary">
                                    {{ $courseRegister->student->user->clue->familiarityWay->title ?? '---' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-warning">
                                    {{ $courseRegister->secretary->user->fullName }}
                                </span>
                            </td>
                            <td class="text-success">
                                <span class="badge bg-label-success">
                                    {{ number_format($courseRegister->paid_amount) }}
                                </span>
                            </td>
                            <td class="text-danger">
                                <span class="badge bg-label-danger">
                                    {{ number_format($courseRegister->debt()) }}
                                </span>
                            </td>
                            <td>
                                @if ($courseRegister->get_pack)
                                    <span class="badge bg-label-success">بله</span>
                                @else
                                    <span class="badge bg-label-danger">خیر</span>
                                @endif
                            </td>
                            <td>{{ $courseRegister->amount_description }}</td>
                            @php
                                $remainingAmount = $courseRegister->amount > 0 ? $courseRegister->amount - $courseRegister->paid_amount : $courseRegister->course->price - $courseRegister->paid_amount;
                            @endphp
                            <td class='text-center'>
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('course-registers.create', ['clue_id' => $courseRegister->student->user->clue->id, 'back_url' => route('courses.course-students', $courseRegister->course_id, [], false)]) }}"
                                        class="dropdown-item cursor-pointer">
                                        <i class="bx bx-plus me-1 text-info"></i> {{ __('clues.courses') }}
                                    </a>
                                    <a href="{{ route('payments.index', ['user_id' => $courseRegister->student->user_id, 'back_url' => route('courses.course-students', $courseRegister->course_id, [], false)]) }}"
                                        class="dropdown-item cursor-pointer">
                                        <i class="fa-solid fa-money-bill me-1 text-primary"></i> {{ __('payments.page_title') }}
                                    </a>
                                    <span class="dropdown-item cursor-pointer" data-user-id="{{ $courseRegister->student->user_id }}" data-bs-toggle="modal" data-bs-target="#addFollowUpModal">
                                        <i class="bx bx-time-five me-1 text-warning"></i> {{ __('follow_ups.add_follow_up') }}
                                    </span>
                                    <a href="{{ route('follow-ups.index', ['user_id' => $courseRegister->student->user_id, 'back_url' => route('courses.course-students', $courseRegister->course_id, [], false)]) }}"
                                        class="dropdown-item cursor-pointer">
                                        <i class="bx bx-timer me-1 text-danger"></i> {{ __('follow_ups.see_follow_ups') }}
                                    </a>
                                    @if (Auth::user()->hasPermissionTo(PermissionTitle::COURSE_REGISTER_CREATE_PRICE_CAN_CHANGE))
                                        <span class="dropdown-item cursor-pointer" wire:click='setCourseRegisterId({{ $courseRegister->id }})' data-bs-toggle="modal"
                                            data-bs-target="#changeCourseRegisterAmountModal"><i class="bx bx-edit me-1 text-primary"></i> تغییر مبلغ دوره
                                        </span>
                                    @endif
                                    @if (!$courseRegister->get_pack)
                                        <a class="dropdown-item cursor-pointer send-group-sms-button" wire:click="setGetPack({{ $courseRegister->id }})">
                                            <i class="bx bxs-gift me-1 text-success"></i>پک دریافت شد
                                        </a>
                                    @endif
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
    </div>
    <div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4 mt-0 mt-md-n2">
                        <h3 class="secondary-font">{{ __('payments.add_payment') }}</h3>
                    </div>
                    <form id="addNewCCForm" class="row g-3" action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 pt-3">
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="course">{{ __('course_registers.course') }}</label>
                                <select wire:model.live="courseRegister" name="paymentable_id" id="payment-course-id" class="form-select" data-allow-clear="true"
                                    data-placeholder="{{ __('course_registers.select_course') }}">
                                    <option value="">---</option>
                                    @foreach ($courseRegisters as $courseRegister)
                                        <option value="{{ $courseRegister->id }}" data-remaining-amount="{{ $remainingAmount }}">
                                            {{ $courseRegister->course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="remaining-amount">{{ __('payments.remaining_amount') }}</label>
                                <input wire:model="remainingAmount" type="text" id="remaining-amount" class="form-control text-start" disabled>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6 mb-1">
                                <label class="form-label" for="paid-amount">{{ __('course_registers.paid_amount') }}</label>
                                <input name="paid_amount" value="{{ old('paid_amount') }}" type="text" id="paid-amount" class="form-control text-start"
                                    placeholder="{{ __('course_registers.paid_amount_placeholder') }}">
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="pay-date">{{ __('course_registers.pay_date') }}</label>
                                <input name="pay_date" type="text" id="pay-date" class="form-control dob-picker" placeholder="{{ __('course_registers.pay_date_placeholder') }}"
                                    value="{{ old('pay_date') }}">
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
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12 col-sm-6 mb-3">
                                <label class="form-label" for="paid_image">تصویر فیش پرداخت </label> {{ requireSign() }}
                                <input name="paid_image" type="file" id="paid_image" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-4">
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
    {{-- send sms modal --}}
    <div class="modal fade" id="sendGroupSmsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mt-0 mt-md-n2">
                        <h3 class="secondary-font">ارسال پیام</h3>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-12 mb-1">
                            <label class="form-label" for="sms_message">{{ __('clues.sms_message') }}</label>
                            <textarea wire:model="smsMessage" rows="5" id="sms_message" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            {{ __('public.cancel') }}
                        </button>
                        <button wire:click="sendSms()" class="btn btn-primary me-sm-3 me-1" data-bs-dismiss="modal" aria-label="Close">{{ __('public.send') }}</button>
                    </div>
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

    @include('components.modals.add-follow-up-modal')
    <style>
        .icon-link:hover {
            color: #fff;
        }

        table th,
        table td {
            padding-right: 10px !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#paid-amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    this.value = new Intl.NumberFormat().format(this.value);
                }
            });

            $('#payment-course-id').on('change', function() {
                $('#paymentable_id').val($(this).find(":selected").val())
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

        function setCourseRegisterId(courseRegisterId) {
            @this.setCourseRegisterId(courseRegisterId);
        }
    </script>
</div>
