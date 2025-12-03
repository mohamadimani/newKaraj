@extends('admin.layouts.master')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header heading-color">{{ __('courses.create') }}</h5>
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif
        @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                <li class="text-danger">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form id="general-form-validation" class="card-body" action="{{ route('courses.store') }}" method="POST">
            @csrf
            <livewire:Admin.Courses.Create />
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="start-date">{{ __('courses.start_date') }}</label>
                    <input name="start_date" type="text" id="start-date" class="form-control dob-picker" placeholder="{{ __('users.form.date_placeholder') }}" value="{{ old('start_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="end-date">{{ __('courses.end_date') }}</label>
                    <input name="end_date" type="text" id="end-date" class="form-control dob-picker" placeholder="{{ __('users.form.date_placeholder') }}" value="{{ old('end_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="start-time">{{ __('courses.start_time') }}</label>
                    <select name="start_time" id="start-time" class="form-control">
                        <option value="">{{ __('users.form.time_placeholder') }}</option>
                        @for ($i = 7; $i < 23; $i++)
                            @for ($j=0; $j < 60; $j +=15)
                            <option {{ old('start_time') == sprintf('%02d:%02d', $i, $j) ? 'selected':'' }} value="{{ sprintf('%02d:%02d', $i, $j) }}">{{ sprintf('%02d:%02d', $i, $j) }}</option>
                            @endfor
                            @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="end-time">{{ __('courses.end_time') }}</label>
                    <select name="end_time" id="end_time" class="form-control">
                        <option value="">{{ __('users.form.time_placeholder') }}</option>
                        @for ($i = 7; $i < 23; $i++)
                        @for ($j=0; $j < 60; $j +=15)
                            <option {{ old('end_time') == sprintf('%02d:%02d', $i, $j) ? 'selected':'' }} value="{{ sprintf('%02d:%02d', $i, $j) }}">{{ sprintf('%02d:%02d', $i, $j) }}</option>
                            @endfor
                            @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('courses.week_days_placeholder') }}</label>
                    <div class="form-check mt-1">
                        <input name='setweek' wire:click="setWeekDays([0,1,2,3,4])" class="form-check-input fast-week" type="radio" value="[0,1,2,3,4]" id="weekDays1">
                        <label class="form-check-label" for="weekDays1">{{ __('courses.saturday_to_wednesday') }}</label>
                    </div>
                    <div class="form-check">
                        <input name='setweek' wire:click="setWeekDays([5,6])" class="form-check-input fast-week" type="radio" value="[5,6]" id="weekDays2">
                        <label class="form-check-label" for="weekDays2">{{ __('courses.thursday_and_friday') }}</label>
                    </div>
                    <div class="form-check">
                        <input name='setweek' wire:click="setWeekDays([5])" class="form-check-input fast-week" type="radio" value="[5]" id="weekDays3">
                        <label class="form-check-label" for="weekDays3">{{ __('courses.thursday') }}</label>
                    </div>
                    <div class="form-check">
                        <input name='setweek' wire:click="setWeekDays([0,2,4])" class="form-check-input fast-week" type="radio" value="[0,2,4]" id="weekDays4">
                        <label class="form-check-label" for="weekDays4">{{ __('courses.even_days') }}</label>
                    </div>
                    <div class="form-check">
                        <input name='setweek' wire:click="setWeekDays([1,3,5])" class="form-check-input fast-week" type="radio" value="[1,3,5]" id="weekDays5">
                        <label class="form-check-label" for="weekDays5">{{ __('courses.odd_days') }}</label>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <label class="form-label" for="week_days">{{ __('courses.week_days') }}</label>
                    <select name="week_days[]" id="week_days" class="select2 form-select" multiple data-allow-clear="true" data-placeholder="{{ __('courses.week_days_placeholder') }}">
                        @foreach ($weekDays as $weekDay)
                        <option value="{{ $weekDay['id'] }}" {{ old('week_days') ? (in_array($weekDay['id'], old('week_days')) ? 'selected' : '') : '' }}>{{ $weekDay['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="pt-4 text-end">
                <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('courses.index') }}">{{ __('public.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.fast-week').on('click', function() {
            $('#week_days').val(JSON.parse($(this).val()));
            $('#week_days').trigger('change');
        });
    });
</script>
<script src="{{ asset('admin-panel/assets/js/validations/course-form-validation.js') }}"></script>
@endsection