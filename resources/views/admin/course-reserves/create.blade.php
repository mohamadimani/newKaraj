@extends('admin.layouts.master')
@php
    use App\Models\CourseRegister;
    use App\Models\Clue;

    $currentClue = Clue::find(request()->query('clue_id'));
@endphp
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        <div class="card mb-4">
            <h5 class="card-header heading-color">{{ __('course_reserves.create') }}</h5>
            @if (Session::has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('error') }}
                </div>
            @endif
            <form id="general-form-validation" class="card-body" action="{{ route('course-reserves.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    @if ($errors->any())
                        <div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-md-4 col-sm-6">
                        <label class="form-label" for="clue">{{ __('course_registers.clue') }}</label>{{ requireSign() }}
                        <select name="clue_id" id="clue" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_registers.select_clue') }}"
                            {{ request()->query('clue_id') ? 'disabled' : '' }} required>
                            <option value="">---</option>
                            @foreach ($clues as $clue)
                                <option value="{{ $clue->id }}" {{ old('clue_id', request()->query('clue_id')) == $clue->id ? 'selected' : '' }}>{{ $clue->user->fullName }}</option>
                            @endforeach
                        </select>
                        @if (request()->query('clue_id'))
                            <input type="hidden" name="clue_id" value="{{ request()->query('clue_id') }}">
                            <input type="hidden" name="redirect_back" value="1">
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label class="form-label" for="phone-internal">{{ __('course_reserves.secretary') }}</label>{{ requireSign() }}
                        <select name="secretary_id" id="secretary" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_reserves.select_secretary') }}"
                            {{ request()->query('secretary_id') ? 'disabled' : '' }} required>
                            <option value="">---</option>
                            @foreach ($secretaries as $secretary)
                                <option value="{{ $secretary->id }}" {{ old('secretary_id', request()->query('secretary_id')) == $secretary->id ? 'selected' : '' }}>
                                    {{ $secretary->user->fullName }}
                                </option>
                            @endforeach
                        </select>
                        @if (request()->query('secretary_id'))
                            <input type="hidden" name="secretary_id" value="{{ request()->query('secretary_id') }}">
                        @endif
                    </div>
                </div>
                <div class="row g-3 pt-3">
                    <div class="col-md-4 col-sm-6 mb-1">
                        <label class="form-label" for="profession-id">{{ __('course_reserves.profession') }}</label>{{ requireSign() }}
                        <select name="profession_id" id="profession-id" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_reserves.select_profession') }}">
                            <option value="">---</option>
                            @foreach ($professions as $profession)
                                <option value="{{ $profession->id }}" data-price="{{ $profession->public_price }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>
                                    {{ $profession->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-1">
                        <label class="form-label" for="tuition-fee">{{ __('course_reserves.tuition_fee') }}</label>
                        <input name="tuition_fee" value="{{ old('tuition_fee') }}" type="text" id="tuition-fee" class="form-control text-start" placeholder="---" disabled>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-1">
                        <label class="form-label" for="course-reserve-description">{{ __('course_reserves.course_reserve_description') }}</label>
                        <input name="course_reserve_description" value="{{ old('course_reserve_description') }}" type="text" id="course-reserve-description" class="form-control text-start"
                            placeholder="{{ __('course_reserves.course_reserve_description_placeholder') }}">
                    </div>
                </div>
                <div class="row g-3 pt-3">
                    <div class="col-md-3 col-sm-6 mb-1">
                        <label class="form-label" for="reserve-paid-amount">{{ __('course_registers.paid_amount') }}</label>{{ requireSign() }}
                        <input name="paid_amount" value="{{ old('paid_amount') }}" type="text" id="reserve-paid-amount" class="form-control text-start"
                            placeholder="{{ __('course_registers.paid_amount_placeholder') }}" required>
                        <small class="text-muted" id="reserve-persian-number"></small>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="pay-date">{{ __('course_registers.pay_date') }}</label>{{ requireSign() }}
                        <input name="pay_date" type="text" id="pay-date" class="form-control dob-picker" placeholder="{{ __('course_registers.pay_date_placeholder') }}"
                            value="{{ old('pay_date') }}" required>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1">
                        <label class="form-label" for="payment-method">{{ __('course_registers.payment_method') }}</label>{{ requireSign() }}
                        <select name="payment_method_id" id="payment-method" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('course_registers.select_payment_method') }}" required>
                            <option value="">---</option>
                            @foreach ($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->title }}</option>
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
                        href="{{ request()->query('back_url') ? request()->query('back_url') : route('course-reserves.index', [], false) }}">
                        {{ __('public.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
                </div>
            </form>
        </div>
        @if (request()->query('clue_id'))
            <div class="card mt-4">
                <h5 class="card-header heading-color">{!! __('course_reserves.user_reserved_courses', ['user' => "<b class='text-primary'>{$currentClue->user->fullName}</b>"]) !!}</h5>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('course_reserves.profession') }}</th>
                                <th>{{ __('course_reserves.paid_amount') }}</th>
                                <th>{{ __('public.status') }}</th>
                                <th>{{ __('public.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $i = 1;
                            @endphp

                            @foreach ($clueCourseReserves as $courseReserve)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <span class="badge bg-label-warning">
                                            {{ $courseReserve->profession->title }}
                                        </span>
                                    </td>
                                    <td class="text-success">{{ number_format($courseReserve->paid_amount) }}</td>
                                    <td>
                                        <span class="badge bg-label-{{ $courseReserve->status->getColor() }} me-1">
                                            {{ $courseReserve->status->getLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="">
                                            @php
                                                $remainingAmount = $courseReserve->profession->price - $courseReserve->paid_amount;
                                            @endphp
                                            <button type="button" data-paymentable-id="{{ $courseReserve->id }}" data-profession-id="{{ $courseReserve->profession_id }}"
                                                data-remaining-amount="{{ $courseReserve->profession->price - $courseReserve->paid_amount }}"
                                                class="btn rounded-pill btn-icon btn-label-primary add-payment-button" data-bs-toggle="modal" {{ $remainingAmount == 0 ? 'disabled' : '' }}
                                                data-bs-target="#addNewCCModal">
                                                <span class="tf-icons bx bx-dollar" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('course_reserves.add_payment') }}"></span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($clueCourseReserves) === 0)
                        <div class="text-center py-5">
                            {{ __('messages.empty_table') }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#profession-id').on('select2:select', function(e) {
                const price = e.params.data.element.dataset.price;
                $('#tuition-fee').val(new Intl.NumberFormat().format(price));
                $('#tuition-fee').prop('disabled', true);
            });
            const selectedCourseId = $('#course-id').val();
            if (selectedCourseId) {
                const selectedCourseElement = $('#course-id').find('option:selected');
                const price = selectedCourseElement.data('price');
                $('#tuition-fee').val(new Intl.NumberFormat().format(price));
                $('#tuition-fee').prop('disabled', true);
            }
            $('.add-payment-button').on('click', function() {
                $('#paymentable_id').val($(this).data('paymentable-id'));
                $('#payment-course-id option[value="' + $(this).data('course-id') + '"]').prop('selected', true);
                $('#select2-payment-course-id-container').text($('#payment-course-id option:selected').text());
                $('#payment-course-id').prop('disabled', true);
                $('#remaining-amount').val(new Intl.NumberFormat().format($(this).data('remaining-amount')));
                $('#remaining-amount').prop('disabled', true);
            });
            $('#reserve-paid-amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    this.value = new Intl.NumberFormat().format(this.value);
                    const numericValue = parseInt(this.value.replace(/,/g, ''));
                    const persianText = numberToPersianText(numericValue);
                    $('#reserve-persian-number').text(persianText + ' تومان');
                } else {
                    $('#reserve-persian-number').text('');
                }
            });
            $('#paid-amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    this.value = new Intl.NumberFormat().format(this.value);
                }
            });
        });
    </script>
@endsection
