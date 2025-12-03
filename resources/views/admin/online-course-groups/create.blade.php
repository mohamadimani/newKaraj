@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card pb-3">
                    <div class="card-body">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 text-gray-800">افزودن گروه جدید</h1>
                            <a href="{{ route('online-course-groups.index') }}" class="btn btn-primary">
                                <i class="fas fa-list"></i> لیست گروه ها
                            </a>
                        </div>
                        @include('admin.layouts.alerts')
                        <div class="overflow-x-auto">
                            <form action="{{ route('online-course-groups.store') }}" method="post">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="name">نام گروه</label>{{ requireSign() }}
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="online_courses">دوره ها</label>{{ requireSign() }}
                                    <select name="online_courses[]" id="online_courses" class="form-control select2" multiple>
                                        @foreach ($onlineCourses as $onlineCourse)
                                            <option value="{{ $onlineCourse->id }}" {{ in_array($onlineCourse->id, old('online_courses') ?? []) ? 'selected' : '' }}>{{ $onlineCourse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary w-100">ثبت</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
