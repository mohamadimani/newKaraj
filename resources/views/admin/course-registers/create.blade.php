@extends('admin.layouts.master')
@php
    use App\Models\CourseRegister;
    use App\Models\Clue;
    use App\Constants\PermissionTitle;

    $currentClue = request()->query('clue_id') ? Clue::find(request()->query('clue_id')) : null;

@endphp
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header heading-color">{{ __('course_registers.create') }}</h5>
            @include('admin.layouts.alerts')
            <form id="general-form-validation" class="card-body" action="{{ route('course-registers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4 col-sm-6">
                        <label class="form-label" for="clue">{{ __('course_registers.clue') }}</label> {{ requireSign() }}
                        @if (request()->query('clue_id'))
                            <input type="text" name="clue" class="form-control" value="{{ $clue->user->full_name . ' (' . $clue->user->wallet . ')'  }}" disabled>
                            <input type="hidden" name="clue_id" value="{{ request()->query('clue_id') }}">
                            <input type="hidden" name="redirect_back" value="1">
                        @else
                            <select name="clue_id" id="clue" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_registers.select_clue') }}"
                                {{ request()->query('clue_id') ? 'disabled' : '' }}>
                                <option value="">---</option>
                                @foreach ($clues as $clue)
                                    <option value="{{ $clue->id }}" {{ old('clue_id', request()->query('clue_id')) == $clue->id ? 'selected' : '' }}>
                                        {{ $clue->user->fullName . ' (' . $clue->user->wallet . ')' }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label class="form-label" for="phone-internal">{{ __('course_registers.phone_internal') }}</label> {{ requireSign() }}
                        <select name="phone_internal_id" id="phone-internal" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_registers.select_phone_internal') }}"
                            {{ request()->query('phone_internal_id') ? 'disabled' : '' }}>
                            <option value="">---</option>
                            @foreach ($phoneInternals as $phoneInternal)
                                <option value="{{ $phoneInternal->id }}"
                                    {{ old('phone_internal_id', request()->query('phone_internal_id')) == $phoneInternal->id || count($phoneInternals) == 1 ? 'selected' : '' }}>
                                    {{ $phoneInternal->phone->number . ' - ' . $phoneInternal->number . ' (' . $phoneInternal->title . ')' }}
                                </option>
                            @endforeach
                        </select>
                        @if (request()->query('phone_internal_id'))
                            <input type="hidden" name="phone_internal_id" value="{{ request()->query('phone_internal_id') }}">
                        @endif
                    </div>
                </div>
                <div class="row g-3 pt-3">
                    <div class="col-md-6 col-sm-6 mb-1">
                        <label class="form-label" for="course">{{ __('course_registers.course') }}</label> {{ requireSign() }}
                        <select name="course_id" id="course-id" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_registers.select_course') }}">
                            <option value="">---</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }} data-price="{{ $course->price }}">
                                    {{ $course->title }} -
                                    (ظرفیت : {{ $course->capacity - $course->courseRegisters->count() }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1">
                        <label class="form-label" for="tuition-fee">{{ __('course_registers.tuition_fee') }}</label>
                        <input name="amount" value="{{ old('amount') }}" type="text" id="tuition-fee" class="form-control text-start"
                            placeholder="{{ __('course_registers.tuition_fee_placeholder') }}" @if (!Auth::user()->hasPermissionTo(PermissionTitle::COURSE_REGISTER_CREATE_PRICE_CAN_CHANGE)) disabled @endif>
                        <small class="text-muted" id="amount-persian-text"></small>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1">
                        <label class="form-label" for="course-register-description">{{ __('course_registers.course_register_description') }}</label>
                        <input name="description" value="{{ old('description') }}" type="text" id="course-register-description" class="form-control text-start"
                            placeholder="{{ __('course_registers.course_register_description_placeholder') }}">
                    </div>
                </div>
                <div class="row g-3 pt-3">
                    <div class="col-md-3 col-sm-6 mb-1">
                        <label class="form-label" for="register-paid-amount">{{ __('course_registers.paid_amount') }}</label>
                        <input name="register_paid_amount" value="{{ old('register_paid_amount') }}" type="text" id="register-paid-amount" class="form-control text-start"
                            placeholder="{{ __('course_registers.paid_amount_placeholder') }}">
                        <small class="text-muted" id="persian-text"></small>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="pay-date">{{ __('course_registers.pay_date') }}</label>
                        <input data-jdp name="pay_date" type="text" id="pay-date" class="form-control" placeholder="{{ __('course_registers.pay_date_placeholder') }}" value="{{ old('pay_date') }}">
                        @include('admin.layouts.jdp', ['time' => true])
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1">
                        <label class="form-label" for="payment-method">{{ __('course_registers.payment_method') }}</label>
                        <select name="payment_method_id" id="payment-method" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_registers.select_payment_method') }}">
                            <option value="">---</option>
                            @foreach ($paymentMethods as $paymentMethod)
                                @if ($paymentMethod->id == 16 and request()->query('clue_id'))
                                    <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>
                                        {{ $paymentMethod->title . ' (' . number_format($clue->user->wallet) . ') ' }}</option>
                                @else
                                    <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1">
                        <label class="form-label" for="payment-description">{{ __('course_registers.payment_description') }}</label>
                        <input name="payment_description" value="{{ old('payment_description') }}" type="text" id="payment-description" class="form-control text-start"
                            placeholder="{{ __('course_registers.payment_description_placeholder') }}">
                    </div>
                </div>
                <div class="row g-3 pt-3">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="paid_image">تصویر فیش پرداخت </label> {{ requireSign() }}
                        <input name="paid_image" type="file" id="paid_image" class="form-control" required>
                    </div>
                </div>
                <div class="pt-4 text-end">
                    <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger"
                        href="{{ request()->query('back_url') ? request()->query('back_url') : route('course-registers.index', [], false) }}">
                        {{ __('public.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary submit">{{ __('public.submit') }}</button>
                </div>
            </form>
        </div>
        @if (request()->query('clue_id'))
            <div class="card mt-4">
                <h5 class="card-header heading-color">
                    @if ($currentClue)
                        {!! __('course_registers.user_registered_courses', ['user' => '<b class="text-primary">' . $currentClue->user->fullName]) . '</b>' !!}
                    @else
                        {{ __('course_registers.registered_courses') }}
                    @endif
                </h5>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('course_registers.course') }}</th>
                                <th>{{ __('course_registers.paid_amount') }}</th>
                                <th>{{ __('course_registers.remaining_amount') }}</th>
                                <th>{{ __('course_registers.first_register') }}</th>
                                <th>{{ __('public.status') }}</th>
                                <th>{{ __('public.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                            @endphp

                            @foreach ($clueCourseRegisters as $courseRegister)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <a href="{{ route('courses.course-students', $courseRegister->course->id) }}">
                                            {{ $courseRegister->course->title }}
                                        </a>
                                    </td>
                                    <td class="text-success">{{ number_format($courseRegister->paid_amount) }}</td>
                                    <td class="text-danger">
                                        @php
                                            $remainingAmount =
                                                $courseRegister->amount > 0 ? $courseRegister->amount - $courseRegister->paid_amount : $courseRegister->course->price - $courseRegister->paid_amount;
                                        @endphp
                                        @if ($remainingAmount > 0)
                                            {{ number_format($remainingAmount) }}
                                        @else
                                            <span class="badge bg-label-info">{{ __('payments.settled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $courseRegister->is_first_register ? 'success' : 'danger' }}">
                                            {{ $courseRegister->is_first_register ? __('course_registers.is_first_register') : __('course_registers.is_not_first_register') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $courseRegister->status->getColor() }} me-1">
                                            {{ $courseRegister->status->getLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class=" ">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="{{ route('payments.index', [
                                                    'user_id' => $courseRegister->student->user->id,
                                                    'back_url' => route('course-registers.create', ['clue_id' => $courseRegister->student->user->clue->id, 'back_url' => request()->query('back_url')], false),
                                                ]) }}"
                                                    class="dropdown-item">
                                                    <i class="tf-icons fa-solid fa-money-bill"></i> {{ __('payments.page_title') }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($clueCourseRegisters) === 0)
                        <div class="text-center py-5">
                            {{ __('messages.empty_table') }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <script src="{{ asset('admin-panel/assets/js/validations/clue-form-validation.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('button.submit').on('click', function() {
                $('button.submit').attr('disabled', 'disabled');
            });

            $('#course-id').on('select2:select', function(e) {
                const price = e.params.data.element.dataset.price;
                $('#tuition-fee').val(new Intl.NumberFormat().format(price));
                // $('#tuition-fee').prop('disabled', true);
            });
            const selectedCourseId = $('#course-id').val();
            if (selectedCourseId) {
                const selectedCourseElement = $('#course-id').find('option:selected');
                const price = selectedCourseElement.data('price');
                $('#tuition-fee').val(new Intl.NumberFormat().format(price));
                // $('#tuition-fee').prop('disabled', true);
            }
            $('.add-payment-button').on('click', function() {
                $('#paymentable_id').val($(this).data('paymentable-id'));
                $('#payment-course-id option[value="' + $(this).data('course-id') + '"]').prop('selected', true);
                $('#select2-payment-course-id-container').text($('#payment-course-id option:selected').text());
                $('#payment-course-id').prop('disabled', true);
                $('#remaining-amount').val(new Intl.NumberFormat().format($(this).data('remaining-amount')));
                $('#remaining-amount').prop('disabled', true);
            });
            const paidAmountInput = document.getElementById('register-paid-amount');
            const persianTextElement = document.getElementById('persian-text');

            paidAmountInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                const numericValue = parseInt(this.value.replace(/,/g, ''));
                if (!isNaN(numericValue)) {
                    persianTextElement.textContent = numberToPersianText(numericValue) + ' تومان';
                } else {
                    persianTextElement.textContent = '';
                }
            });
            $('#paid-amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    this.value = new Intl.NumberFormat().format(this.value);
                }


            });
            const paymentPaidAmountInput = document.getElementById('paid-amount');
            const paymentPersianTextElement = document.getElementById('payment-persian-number');

            paymentPaidAmountInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                const numericValue = parseInt(this.value.replace(/,/g, ''));
                if (!isNaN(numericValue)) {
                    paymentPersianTextElement.textContent = numberToPersianText(numericValue) + ' تومان';
                } else {
                    paymentPersianTextElement.textContent = '';
                }
            });


            const AmountInput = document.getElementById('tuition-fee');
            const amountPersianTextElement = document.getElementById('amount-persian-text');

            AmountInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                const amountNumericValue = parseInt(this.value.replace(/,/g, ''));
                if (!isNaN(amountNumericValue)) {
                    amountPersianTextElement.textContent = numberToPersianText(amountNumericValue) + ' تومان';
                } else {
                    amountPersianTextElement.textContent = '';
                }
            });
        });
    </script>
@endsection
