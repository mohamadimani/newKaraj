@extends('admin.layouts.master')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> تغییر دوره : <span class="text-info">{{ $courseRegister->student->user->full_name }}</span></h3>
                    </div>
                    <div class="card-body">
                        @include('admin.layouts.alerts')
                        <form action="{{ route('course-registers.update', $courseRegister->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>دوره فعلی</label>
                                        <input type="text" class="form-control" value="{{ $courseRegister->course->title }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>تغییر جدید</label>
                                        <select name="course_id" class="form-control select2">
                                            <option value="">انتخاب دوره</option>
                                            @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id')==$course->id ? 'selected' : '' }}>
                                                {{ $course->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <div class="form-group">
                                        <label>توضیحات</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
                                    <a href="{{ route('course-registers.index') }}" class="btn btn-secondary">انصراف</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection