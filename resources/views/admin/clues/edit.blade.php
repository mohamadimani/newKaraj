@extends('admin.layouts.master')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header heading-color">{{ __('clues.edit') }}</h5>
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif
        <form id="general-form-validation" class="card-body" action="{{ route('clues.update', $clue->id) }}" method="POST">
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
                <div class="col-md-3 col-sm-6 mb-3">
                    <label class="form-label" for="first-name">{{ __('users.first_name') }}</label>
                    <input name="first_name" value="{{ old('first_name', $clue->user->first_name) }}" type="text" id="first-name" class="form-control text-start"
                        placeholder="{{ __('users.form.first_name_placeholder') }}">
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <label class="form-label" for="last-name">{{ __('users.last_name') }}</label>
                    <input name="last_name" value="{{ old('last_name', $clue->user->last_name) }}" type="text" id="last-name" class="form-control text-start"
                        placeholder="{{ __('users.form.last_name_placeholder') }}">
                </div>
                <div class="col-md-2 col-sm-6 mb-3">
                    <label class="form-label" for="mobile">{{ __('users.mobile') }}</label>
                    <input name="mobile" value="{{ old('mobile', $clue->user->mobile) }}" type="text" id="mobile" class="form-control" placeholder="{{ __('users.form.mobile_placeholder') }}" disabled
                        style="direction: ltr;">
                </div>
                <div class="col-md-1 col-sm-6 mb-3">
                    <label class="form-label" for="gender">{{ __('users.gender') }}</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="">---</option>
                        <option value="male" {{ old('gender', $clue->user->gender) == 'male' ? 'selected' : '' }}>{{ __('users.gender_male') }}</option>
                        <option value="female" {{ old('gender', $clue->user->gender) == 'female' ? 'selected' : '' }}>{{ __('users.gender_female') }}</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <label class="form-label" for="province">{{ __('users.province') }}</label>
                    <select name="province_id" id="province" class="form-select">
                        <option value="">---</option>
                        @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ old('province_id', $clue->user->province_id) == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="familiarity-ways">{{ __('clues.familiarity_ways') }}</label>
                    <select name="familiarity_way_id" id="familiarity-ways" class="select2 form-select" data-allow-clear="true" data-placeholder="{{ __('clues.select_familiarity_ways') }}">
                        <option value="">---</option>
                        @foreach($familiarityWays as $familiarityWay)
                        <option value="{{ $familiarityWay->id }}" {{ old('familiarity_way_id', $clue->familiarity_way_id) == $familiarityWay->id ? 'selected' : '' }}>{{ $familiarityWay->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="professions">{{ __('clues.professions') }}</label>
                    <select name="profession_ids[]" id="professions" class="select2 form-select" multiple data-allow-clear="true" data-placeholder="{{ __('clues.select_professions') }}">
                        <option value="">---</option>
                        @foreach($professions as $profession)
                        <option value="{{ $profession->id }}" {{ old('profession_ids', $clue->professions()->pluck('id')->toArray()) !== null && in_array($profession->id, old('profession_ids',
                            $clue->professions()->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $profession->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="branch_id">{{ __('clues.branch') }}</label>
                    <select name="branch_id" id="professions" class="form-select" data-allow-clear="true" data-placeholder="{{ __('clues.select_professions') }}">
                        <option value="">---</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $clue->branch_id) !== null && $branch->id == old('$branch', $clue->branch_id) ? 'selected' : '' }}>{{ $branch->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="pt-4 text-end">
                <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('clues.index') }}">{{ __('public.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('admin-panel/assets/js/validations/clue-form-validation.js') }}"></script>
@endsection