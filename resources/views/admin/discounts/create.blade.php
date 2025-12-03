@extends('admin.layouts.master')
@php
    use App\Enums\Discount\AmountTypeEnum;
    use App\Enums\Discount\DiscountTypeEnum;
@endphp
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header heading-color">{{ __('discounts.create') }}</h5>
            @if (Session::has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('error') }}
                </div>
            @endif
            @include('admin.layouts.jdp', ['time' => true])
            <form id="general-form-validation" class="card-body" action="{{ route('discounts.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-3">
                        <label class="form-label" for="title">{{ __('discounts.title') }}</label>
                        <input value="{{ old('title') }}" type="text" name="title" id="title" class="form-control" placeholder="{{ __('discounts.title_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="code">{{ __('discounts.code') }}</label>
                        <input value="{{ old('code') }}" type="text" name="code" id="code" class="form-control" placeholder="{{ __('discounts.code_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="amount">{{ __('discounts.amount') }}</label>
                        <input value="{{ old('amount') }}" type="text" name="amount" id="amount" class="form-control" placeholder="{{ __('discounts.amount_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="amount_type">{{ __('discounts.amount_type') }}</label>
                        <select name="amount_type" id="amount_type" class="form-select">
                            <option value="">---</option>
                            @foreach (AmountTypeEnum::cases() as $amountType)
                                <option value="{{ $amountType->value }}" {{ old('amount_type') == $amountType->value ? 'selected' : '' }}>
                                    {{ $amountType->name() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="minimum_order_amount">{{ __('discounts.minimum_order_amount') }}</label>
                        <input value="{{ old('minimum_order_amount') }}" type="text" name="minimum_order_amount" id="minimum_order_amount" class="form-control"
                            placeholder="{{ __('discounts.minimum_order_amount_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="available_from">{{ __('discounts.available_from') }}</label>
                        <input data-jdp value="{{ old('available_from') }}" type="text" name="available_from" id="available_from" class="form-control"
                            placeholder="{{ __('discounts.available_from_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="available_until">{{ __('discounts.available_until') }}</label>
                        <input data-jdp value="{{ old('available_until') }}" type="text" name="available_until" id="available_until" class="form-control "
                            placeholder="{{ __('discounts.available_until_placeholder') }}">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="" class="form-label">{{ __('discounts.for_course') }}</label>
                        <select name="is_online" id="is_online" class="form-select">
                            <option value="1">آنلاین</option>
                            <option value="0">حضوری</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="usage_limit">{{ __('discounts.usage_limit') }}</label>
                        <input value="{{ old('usage_limit') }}" type="number" name="usage_limit" id="usage_limit" class="form-control" placeholder="{{ __('discounts.usage_limit_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="discount_type">{{ __('discounts.discount_type') }}</label>
                        <select name="discount_type" id="discount_type" class="form-select">
                            <option value="">---</option>
                            @foreach (DiscountTypeEnum::cases() as $discountType)
                                <option class="" value="{{ $discountType->value }}" {{ old('discount_type') == $discountType->value ? 'selected' : '' }}>
                                    {{ $discountType->name() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1 d-none" id="profession-id-container">
                        <label class="form-label" for="profession">{{ __('discounts.professions') }}</label>
                        <select name="profession_id" id="profession-id" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('discounts.select_profession') }}">
                            <option value="">---</option>
                            @foreach ($professions as $profession)
                                <option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>
                                    {{ $profession->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1 d-none" id="user-id-container">
                        <label class="form-label" for="user">{{ __('discounts.users') }}</label>
                        <select name="user_id" id="user-id" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('discounts.select_user') }}">
                            <option value="">---</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->fullName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1 d-none" id="course-id-container">
                        <label class="form-label" for="course">{{ __('discounts.courses') }}</label>
                        <select name="course_id" id="course-id" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('discounts.select_course') }}">
                            <option value="">---</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1 d-none" id="course-online-id-container">
                        <label class="form-label" for="online_courses">{{ __('discounts.online_courses') }}</label>
                        <select name="online_courses" id="online_courses" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('discounts.select_course') }}">
                            <option value="">---</option>
                            @foreach ($onlineCourses as $onlineCourse)
                                <option value="{{ $onlineCourse->id }}" {{ old('online_courses') == $onlineCourse->id ? 'selected' : '' }}>
                                    {{ $onlineCourse->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12 mb-3 mt-3">
                    <label class="form-label" for="banner">بنر تخفیف</label>
                    <input type="file" name="banner" id="banner" class="form-control">
                </div>
                <div class="pt-4 text-end">
                    <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('discounts.index') }}">{{ __('public.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('admin-panel/assets/js/general-validation.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#minimum_order_amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    this.value = new Intl.NumberFormat().format(this.value);
                }
            });

            $('#is_online').on('change', function() {
                if ($('#is_online').val() == '1') {
                    $('select#discount_type option[value="{{ DiscountTypeEnum::COURSE_ONLINE->value }}"]').removeClass('d-none');
                    $('select#discount_type option[value="{{ DiscountTypeEnum::COURSE->value }}"]').addClass('d-none');
                } else {
                    $('select#discount_type option[value="{{ DiscountTypeEnum::COURSE_ONLINE->value }}"]').addClass('d-none');
                    $('select#discount_type option[value="{{ DiscountTypeEnum::COURSE->value }}"]').removeClass('d-none');
                }
            });
            $('select#discount_type option[value="{{ DiscountTypeEnum::COURSE->value }}"]').addClass('d-none');

            $('#discount_type').on('change', function() {
                $('#profession-id-container').addClass('d-none');
                $('#course-online-id-container').addClass('d-none');
                $('#course-id-container').addClass('d-none');
                $('#user-id-container').addClass('d-none');
                $('#online-courses-id-container').addClass('d-none');

                if (this.value == '{{ DiscountTypeEnum::PROFESSION->value }}') {
                    $('#profession-id-container').removeClass('d-none');
                } else if (this.value == '{{ DiscountTypeEnum::USER->value }}') {
                    $('#user-id-container').removeClass('d-none');
                } else if (this.value == '{{ DiscountTypeEnum::COURSE->value }}') {
                    $('#course-id-container').removeClass('d-none');
                } else if (this.value == '{{ DiscountTypeEnum::COURSE_ONLINE->value }}') {
                    $('#course-online-id-container').removeClass('d-none');
                }

            });
        });
    </script>
@endsection
