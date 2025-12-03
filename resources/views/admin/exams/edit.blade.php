@extends('admin.layouts.master')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header heading-color">ویرایش آزمون</h5>
        @include('admin.layouts.alerts')
        <form id="general-form-validation" class="card-body" action="{{ route('exams.update', $exam->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="profession_id">حرفه</label>
                    @php
                    $professions = $professions->merge($exam->professions);
                    @endphp
                    <select name="profession_id[]" id="profession_id" class="select2 form-select" multiple placeholder="{{ __('courses.select') }}">
                        <option value="">---</option>
                        @foreach ($professions as $profession)
                        <option value="{{ $profession->id }}" {{ old('profession_id', $exam->professions->pluck('id')->toArray()) && in_array($profession->id,old('profession_id', $exam->professions->pluck('id')->toArray()))  ? 'selected' : '' }}>{{ $profession->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="branch_id">شعبه</label>
                    <select name="branch_id" id="branch_id" class="select2 form-select" placeholder="{{ __('courses.select') }}">
                        <option value="">---</option>
                        @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $exam->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <label class="form-label" for="title">عنوان</label>
                    <input name="title" value="{{ old('title', $exam->title) }}" type="text" id="title" class="form-control text-start" placeholder="{{ __('courses.title_placeholder') }}">
                </div>
                <div class="col-md-6 col-sm-6 mb-3">
                    <label class="form-label" for="description">توضیحات</label>
                    <input name="description" value="{{ old('description', $exam->description) }}" type="text" id="description" class="form-control text-start" placeholder="توضیحات">
                </div>
                <div class="col-md-2 col-sm-6 mb-3">
                    <label class="form-label" for="duration_min">مدت آزمون (دقیقه)</label>
                    <input name="duration_min" value="{{ old('duration_min', $exam->duration_min) }}" type="number" id="duration_min" class="form-control text-start"
                        placeholder="{{ __('courses.duration_hours_placeholder') }}">
                </div>
                <div class="col-md-2 col-sm-6 mb-3">
                    <label class="form-label" for="passing_score">نمره قبولی</label>
                    <input name="passing_score" value="{{ old('passing_score', $exam->passing_score) }}" type="number" id="passing_score" class="form-control text-start"
                        placeholder="{{ __('courses.duration_hours_placeholder') }}">
                </div>
                <div class="col-md-2 col-sm-6 mb-3">
                    <label class="form-label" for="question_count">تعداد سوال</label>
                    <input name="question_count" value="{{ old('question_count', $exam->question_count) }}" type="number" id="question_count" class="form-control text-start"
                        placeholder="تعداد سوال">
                </div>
            </div>
            <div class="pt-4 text-end">
                <a class="btn btn-label-secondary me-sm-3 me-1 btn-outline-danger" href="{{ route('exams.index') }}">{{ __('public.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('public.submit') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
