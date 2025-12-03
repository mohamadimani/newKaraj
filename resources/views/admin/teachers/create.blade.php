@extends('admin.layouts.master')

@section('title', 'ایجاد استاد')

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header heading-color">{{ __('teachers.create') }}</h5>
            @if (Session::has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('error') }}
                </div>
            @endif
            <form id=" " class="card-body" action="{{ route('teachers.store') }}" method="POST">
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
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="form-label" for="first-name">{{ __('users.first_name') }}</label> {{ requireSign() }}
                        <input name="first_name" value="{{ old('first_name') }}" type="text" id="first-name" class="form-control text-start" placeholder="{{ __('users.form.first_name_placeholder') }}">
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="form-label" for="last-name">{{ __('users.last_name') }}</label> {{ requireSign() }}
                        <input name="last_name" value="{{ old('last_name') }}" type="text" id="last-name" class="form-control text-start" placeholder="{{ __('users.form.last_name_placeholder') }}">
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="form-label" for="mobile">{{ __('users.mobile') }}</label> {{ requireSign() }}
                        <input name="mobile" value="{{ old('mobile') }}" type="text" id="mobile" class="form-control" placeholder="{{ __('users.form.mobile_placeholder') }}"
                            style="direction: ltr;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="birth-date">{{ __('users.birth_date') }}</label>
                        <input name="birth_date" type="text" id="birth-date" class="form-control dob-picker" placeholder="{{ __('users.form.date_placeholder') }}" value="{{ old('birth_date') }}">
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="form-label" for="gender">{{ __('users.gender') }}</label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="">---</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('users.gender_male') }}</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('users.gender_female') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="start-date">{{ __('public.start_date') }}</label>
                        <input name="start_date" type="text" id="start-date" class="form-control dob-picker" placeholder="{{ __('users.form.date_placeholder') }}" value="{{ old('start_date') }}">
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="form-label" for="province">{{ __('users.province') }}</label> {{ requireSign() }}
                        <select name="province_id" id="province" class="form-select">
                            <option value="">---</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="form-label" for="branch">{{ __('users.branches') }}</label> {{ requireSign() }}
                        <select name="branch_ids[]" id="branch" class="form-select select2" multiple>
                            <option value="">---</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (old('branch_ids') and in_array($branch->id, old('branch_ids'))) ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <label class="form-label" for="profession">{{ __('users.professions') }}</label> {{ requireSign() }}
                        <select name="profession_ids[]" id="profession" class="form-select select2" multiple>
                            <option value="">---</option>
                            @foreach ($professions as $profession)
                                <option value="{{ $profession->id }}" {{ (old('profession_ids') and in_array($profession->id, old('profession_ids'))) ? 'selected' : '' }}>{{ $profession->title }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="pt-4 text-end">
                    <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('teachers.index') }}">{{ __('public.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('admin-panel/assets/js/general-validation.js') }}"></script>
@endsection
