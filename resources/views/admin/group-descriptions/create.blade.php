@extends('admin.layouts.master')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header heading-color">{{ __('group_descriptions.create') }}</h5>
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif
        <form id="general-form-validation" class="card-body" action="{{ route('group-descriptions.store') }}" method="POST">
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
                <div class="col-md-12 mb-3">
                    <label class="form-label" for="profession">{{ __('group_descriptions.professions') }}</label>
                    <select name="profession_ids[]" id="profession" class="select2 form-select" multiple placeholder="{{ __('group_descriptions.select_profession') }}">
                        <option value="">{{ __('group_descriptions.select_profession') }}</option>
                        @foreach ($professions as $profession)
                        <option value="{{ $profession->id }}" {{ in_array($profession->id, old('profession_id') ?? []) ? 'selected' : '' }}>{{ $profession->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label" for="desc">{{ __('group_descriptions.description') }}</label>
                    <textarea name="description" id="description" class="form-control" rows="20">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="pt-4 text-end">
                <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('group-descriptions.index') }}">{{ __('public.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('admin-panel/assets/js/general-validation.js') }}"></script>
@endsection
