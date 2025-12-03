@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header heading-color">{{ __('class_rooms.create') }}</h5>
            @if (Session::has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('error') }}
                </div>
            @endif
            <form id="general-form-validation" class="card-body" action="{{ route('class-rooms.store') }}" method="POST">
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
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="name">{{ __('class_rooms.name') }}</label>
                        <input name="name" value="{{ old('name') }}" type="text" id="name" class="form-control text-start" placeholder="{{ __('class_rooms.form.name_placeholder') }}">
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="number">{{ __('class_rooms.number') }}</label>
                        <input name="number" value="{{ old('number') }}" type="text" id="number" class="form-control text-start" placeholder="{{ __('class_rooms.form.number_placeholder') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="branch">{{ __('branches.name') }}</label>
                        <select name="branch_id" id="branch" class="select2 form-select" placeholder="{{ __('branches.select_branch') }}">
                            <option value="">{{ __('branches.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="profession">{{ __('class_rooms.professions') }}</label>
                        <select name="profession_id[]" id="profession" class="select2 form-select" multiple placeholder="{{ __('class_rooms.select_profession') }}">
                            <option value="">{{ __('class_rooms.select_profession') }}</option>
                            @foreach ($professions as $profession)
                                <option value="{{ $profession->id }}" {{ in_array($profession->id, old('profession_id') ?? []) ? 'selected' : '' }}>{{ $profession->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label class="form-label" for="capacity">{{ __('class_rooms.capacity') }}</label>
                        <input name="capacity" value="{{ old('capacity') }}" type="text" id="capacity" class="form-control text-start"
                            placeholder="{{ __('class_rooms.form.capacity_placeholder') }}">
                    </div>
                </div>
                <div class="pt-4 text-end">
                    <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('class-rooms.index') }}">{{ __('public.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('admin-panel/assets/js/general-validation.js') }}"></script>
@endsection
