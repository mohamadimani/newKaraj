@extends('admin.layouts.master')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header heading-color">{{ __('professions.create') }}</h5>
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif
        <form id="general-form-validation" class="card-body" action="{{ route('professions.store') }}" method="POST">
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
                <div class="col-md-6">
                    <label for="title">{{ __('professions.title') }}</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="{{ __('professions.title_placeholder') }}" value="{{ old('title') }}">
                </div>
                <div class="col-md-6" wire:ignore>
                    <label for="branch_id">{{ __('professions.branches') }}</label>
                    <select name="branch_ids[]" id="branch_ids" class="form-control select2" multiple required>
                        <option value="">{{ __('professions.branches_placeholder') }}</option>
                        @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_ids') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="public_price">{{ __('professions.public_price') }}</label>
                    <input type="text" name="public_price" id="public_price" class="form-control" placeholder="{{ __('professions.public_price_placeholder') }}" value="{{ old('public_price') }}">
                </div>
                <div class="col-md-4">
                    <label for="public_duration_hours">{{ __('professions.public_duration_hours') }}</label>
                    <input type="text" name="public_duration_hours" id="public_duration_hours" class="form-control" placeholder="{{ __('professions.public_duration_hours_placeholder') }}" value="{{ old('public_duration_hours') }}">
                </div>
                <div class="col-md-4">
                    <label for="public_capacity">{{ __('professions.public_capacity') }}</label>
                    <input type="text" name="public_capacity" id="public_capacity" class="form-control" placeholder="{{ __('professions.public_capacity_placeholder') }}" value="{{ old('public_capacity') }}">
                </div>
                <div class="col-md-4">
                    <label for="private_price">{{ __('professions.private_price') }}</label>
                    <input type="text" name="private_price" id="private_price" class="form-control" placeholder="{{ __('professions.private_price_placeholder') }}" value="{{ old('private_price') }}">
                </div>
                <div class="col-md-4">
                    <label for="private_duration_hours">{{ __('professions.private_duration_hours') }}</label>
                    <input type="text" name="private_duration_hours" id="private_duration_hours" class="form-control" placeholder="{{ __('professions.private_duration_hours_placeholder') }}" value="{{ old('private_duration_hours') }}">
                </div>
                <div class="col-md-4">
                    <label for="private_capacity">{{ __('professions.private_capacity') }}</label>
                    <input type="text" name="private_capacity" id="private_capacity" class="form-control" placeholder="{{ __('professions.private_capacity_placeholder') }}" value="{{ old('private_capacity') }}">
                </div>
            </div>
            <div class="pt-4 text-end">
                <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('professions.index') }}">{{ __('public.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('admin-panel/assets/js/general-validation.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#public_price').on('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
            if (this.value) {
                this.value = new Intl.NumberFormat().format(this.value);
            }
        });
        $('#private_price').on('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
            if (this.value) {
                this.value = new Intl.NumberFormat().format(this.value);
            }
        });
    });
</script>
@endsection
