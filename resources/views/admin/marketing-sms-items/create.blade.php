@php
use App\Enums\MarketingSms\TargetTypeEnum;
@endphp

@extends('admin.layouts.master')

@section('title', __('marketing_sms_templates.create_settings'))

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header heading-color">{{ __('marketing_sms_templates.create_settings') }}</h5>
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif
        <form id="marketing-sms-template-form-validation" class="card-body" action="{{ route('marketing-sms-items.store') }}" method="POST">
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
                <label class="form-label fw-bold mb-3">{{ __('marketing_sms_items.after_time') }}  : <span class="text-danger">(حداقل 30 دقیقه)</span></label>
                <div class="row">
                    {{-- <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_seconds">{{ __('marketing_sms_items.after_time_seconds') }}</label>
                        <input name="after_time_seconds" value="{{ old('after_time_seconds', 0) }}" type="number" id="after_time_seconds" class="form-control">
                    </div> --}}
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_minutes">{{ __('marketing_sms_items.after_time_minutes') }}</label>
                        <input name="after_time_minutes" value="{{ old('after_time_minutes', 0) }}" type="number" id="after_time_minutes" class="form-control">
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_hours">{{ __('marketing_sms_items.after_time_hours') }}</label>
                        <input name="after_time_hours" value="{{ old('after_time_hours', 0) }}" type="number" id="after_time_hours" class="form-control">
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_days">{{ __('marketing_sms_items.after_time_days') }}</label>
                        <input name="after_time_days" value="{{ old('after_time_days', 0) }}" type="number" id="after_time_days" class="form-control">
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_months">{{ __('marketing_sms_items.after_time_months') }}</label>
                        <input name="after_time_months" value="{{ old('after_time_months', 0) }}" type="number" id="after_time_months" class="form-control">
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <label class="form-label" for="after_time_years">{{ __('marketing_sms_items.after_time_years') }}</label>
                        <input name="after_time_years" value="{{ old('after_time_years', 0) }}" type="number" id="after_time_years" class="form-control">
                    </div>
                </div>
                <label class="form-label fw-bold mb-3">{{ __('marketing_sms_items.show_more_info') }}</label>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3 d-flex align-items-center">
                        <label class="switch">
                            <input name="show_student_name" type="checkbox" class="switch-input" checked>
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
                            <input name="show_secretary_name" type="checkbox" class="switch-input" checked>
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
                            <input name="show_branch_name" type="checkbox" class="switch-input" checked>
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
                            value="{{ old('discount_amount') }}"
                            type="string"
                            id="discount_amount"
                            class="form-control">
                        <div id="persian-text"></div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label" for="content">{{ __('marketing_sms_items.content') }}</label>
                    <textarea name="content" id="content" class="form-control">{{ old('content') }}</textarea>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="text-light small fw-semibold mb-3">{{ __('public.status') }}</div>
                    <label class="switch">
                        <input name="is_active" type="checkbox" class="switch-input" checked>
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
                <input type="hidden" name="marketing_sms_template_id" value="{{ $marketingSmsTemplate->id }}">
                <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('marketing-sms-templates.settings', $marketingSmsTemplate->id) }}">{{ __('public.cancel') }}</a>
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
