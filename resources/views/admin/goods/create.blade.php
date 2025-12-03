@extends('admin.layouts.master')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 text-gray-800">افزودن اموال جدید</h1>
                <a href="{{ route('goods.index') }}" class="btn btn-primary btn-sm">لیست اموال</a>
            </div>
            @include('admin.layouts.alerts')
            <form action="{{ route('goods.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">نام</label> {{ requireSign() }}
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="code" class="form-label">کد</label> {{ requireSign() }}
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="count" class="form-label">تعداد</label> {{ requireSign() }}
                            <input type="number" class="form-control" id="count" name="count" value="{{ old('count') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">توضیحات</label>
                            <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="branch_id" class="form-label">شعبه</label> {{ requireSign() }}
                            <select class="form-control" id="branch_id" name="branch_id" required>
                                <option value="">انتخاب کنید</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="class_room_id" class="form-label">کلاس</label> {{ requireSign() }}
                            <select class="form-control" id="class_room_id" name="class_room_id" required>
                                <option value="">انتخاب کنید</option>
                                @foreach ($classRooms as $classRoom)
                                    <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>{{ $classRoom->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="health_status" class="form-label">وضعیت سلامت</label> {{ requireSign() }}
                            <select class="form-control" id="health_status" name="health_status" required>
                                <option value="good">سالم</option>
                                <option value="damaged">معیوب</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="image" class="form-label">تصویر</label> {{ requireSign() }}
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">ثبت</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
