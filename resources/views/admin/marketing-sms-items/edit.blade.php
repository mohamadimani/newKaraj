@php
use App\Enums\MarketingSms\TargetTypeEnum;
@endphp

@extends('admin.layouts.master')

@section('title', __('marketing_sms_items.edit'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header heading-color">{{ __('marketing_sms_items.edit') }}</h5>
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif
        <form id="marketing-sms-template-form-validation" class="card-body" action="{{ route('marketing-sms-items.update', $marketingSmsItem->id) }}" method="POST">
            @csrf
            @method('PUT')
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
                <label class="form-label fw-bold mb-3">{{ __('marketing_sms_items.after_time') }}</label>
                <div class="row">
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_seconds">{{ __('marketing_sms_items.after_time_seconds') }}</label>
                        <input name="after_time_seconds" value="{{ old('after_time_seconds', $marketingSmsItem->after_time_details?->seconds) }}" type="number" id="after_time_seconds" class="form-control">
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_minutes">{{ __('marketing_sms_items.after_time_minutes') }}</label>
                        <input name="after_time_minutes" value="{{ old('after_time_minutes', $marketingSmsItem->after_time_details?->minutes) }}" type="number" id="after_time_minutes" class="form-control">
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_hours">{{ __('marketing_sms_items.after_time_hours') }}</label>
                        <input name="after_time_hours" value="{{ old('after_time_hours', $marketingSmsItem->after_time_details?->hours) }}" type="number" id="after_time_hours" class="form-control">
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_days">{{ __('marketing_sms_items.after_time_days') }}</label>
                        <input name="after_time_days" value="{{ old('after_time_days', $marketingSmsItem->after_time_details?->days) }}" type="number" id="after_time_days" class="form-control">
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_months">{{ __('marketing_sms_items.after_time_months') }}</label>
                        <input name="after_time_months" value="{{ old('after_time_months', $marketingSmsItem->after_time_details?->months) }}" type="number" id="after_time_months" class="form-control">
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_years">{{ __('marketing_sms_items.after_time_years') }}</label>
                        <input name="after_time_years" value="{{ old('after_time_years', $marketingSmsItem->after_time_details?->years) }}" type="number" id="after_time_years" class="form-control">
                    </div>
                </div>
                <label class="form-label fw-bold mb-3">{{ __('marketing_sms_items.show_more_info') }}</label>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3 d-flex align-items-center">
                        <label class="switch">
                            <input name="show_student_name" type="checkbox" class="switch-input" {{ old('show_student_name', $marketingSmsItem->include_params?->show_student_name) ? 'checked' : '' }}>
                            <span class="switch-toggle-slider">
                                <span class="switch-on">
                                    <i class="bx bx-check"></i>
                                </span>
                                <span class="switch-off">
                                    <i class="bx bx-x"></i>
                                </span>
                            </span>
                            <span class="switch-label">{{ __('marketing_sms_items.show_student_name') }}</span>
                        </label>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3 d-flex align-items-center">
                        <label class="switch">
                            <input name="show_secretary_name" type="checkbox" class="switch-input" {{ old('show_secretary_name', $marketingSmsItem->include_params?->show_secretary_name) ? 'checked' : '' }}>
                            <span class="switch-toggle-slider">
                                <span class="switch-on">
                                    <i class="bx bx-check"></i>
                                </span>
                                <span class="switch-off">
                                    <i class="bx bx-x"></i>
                                </span>
                            </span>
                            <span class="switch-label">{{ __('marketing_sms_items.show_secretary_name') }}</span>
                        </label>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3 d-flex align-items-center">
                        <label class="switch">
                            <input name="show_branch_name" type="checkbox" class="switch-input" {{ old('show_branch_name', $marketingSmsItem->include_params?->show_branch_name) ? 'checked' : '' }}>
                            <span class="switch-toggle-slider">
                                <span class="switch-on">
                                    <i class="bx bx-check"></i>
                                </span>
                                <span class="switch-off">
                                    <i class="bx bx-x"></i>
                                </span>
                            </span>
                            <span class="switch-label">{{ __('marketing_sms_items.show_branch_name') }}</span>
                        </label>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="discount_amount">{{ __('marketing_sms_items.discount_amount') }}</label>
                        <input
                            name="discount_amount"
                            value="{{ old('discount_amount', number_format($marketingSmsItem->include_params?->discount_amount)) }}"
                            type="string"
                            id="discount_amount"
                            class="form-control">
                        <div id="persian-text"></div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label" for="content">{{ __('marketing_sms_items.content') }}</label>
                    <textarea name="content" id="content" class="form-control">{{ old('content', $marketingSmsItem->content) }}</textarea>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="text-light small fw-semibold mb-3">{{ __('public.status') }}</div>
                    <label class="switch">
                        <input name="is_active" type="checkbox" class="switch-input" {{ old('is_active', $marketingSmsItem->is_active) ? 'checked' : '' }}>
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="bx bx-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="bx bx-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">{{ __('public.status_active') }}</span>
                    </label>
                </div>
            </div>
            <div class="pt-4 text-end">
                <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('marketing-sms-templates.settings', $marketingSmsItem->marketing_sms_template_id) }}">{{ __('public.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('admin-panel/assets/js/validations/marketing-sms-template-form-validation.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#discount_amount').on('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
            if (this.value) {
                this.value = new Intl.NumberFormat().format(this.value);
            }
        });

        const discountAmountInput = document.getElementById('discount_amount');
        const persianTextElement = document.getElementById('persian-text');

        discountAmountInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');

            const numericValue = parseInt(this.value.replace(/,/g, ''));
            if (!isNaN(numericValue)) {
                persianTextElement.textContent = numberToPersianText(numericValue) + ' تومان';
            } else {
                persianTextElement.textContent = '';
            }
        });
    });
</script>
@endsection
