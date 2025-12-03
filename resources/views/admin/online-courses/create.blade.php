@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card pb-3">
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 text-gray-800">افزودن دوره جدید</h1>
                    <a href="{{ route('online-courses.index') }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> لیست دوره های آنلاین
                    </a>
                </div>
                @include('admin.layouts.alerts')
                <form action="{{ route('online-courses.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">نام دوره</label>{{ requireSign() }}
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="spot_key">کلید اسپات پلیر</label>{{ requireSign() }}
                                <input type="text" name="spot_key" id="spot_key" class="form-control" value="{{ old('spot_key') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="amount">مبلغ</label>{{ requireSign() }}
                                <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="duration_hour">طول دوره</label>{{ requireSign() }}
                                <input type="number" name="duration_hour" id="duration_hour" class="form-control" value="{{ old('duration_hour') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="duration_hour">نام استاد</label>{{ requireSign() }}
                                <select name="teacher_id" id="" class="form-control">
                                    <option value="">انتخاب استاد</option>
                                    @foreach ($techers as $techer)
                                        <option value="{{ $techer->id }}" {{ old('teacher_id') == $techer->id ? 'selected' : '' }}>{{ $techer->user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="duration_hour">درصد استاد</label>{{ requireSign() }}
                                <select name="percent" id="" class="form-control">
                                    <option value="">--- انتخاب ---</option>
                                    @for ($a = 0; $a <= 80; $a++)
                                        <option value="{{ $a }}" {{ old('percent') == $a ? 'selected' : '' }}>{{ $a }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="discount_amount">مبلغ تخفیف خورده</label>
                                <input type="number" name="discount_amount" id="discount_amount" class="form-control" value="{{ old('discount_amount') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="discount_start_at_jalali">از تاریخ</label>
                                <input data-jdp type="text" name="discount_start_at_jalali" id="discount_start_at_jalali" class="form-control" value="{{ old('discount_start_at_jalali') }}">
                                @include('admin.layouts.jdp', ['time' => true])
                            </div>
                            <div class="form-group mb-3">
                                <label for="discount_expire_at_jalali">تا تاریخ</label>
                                <input data-jdp type="text" name="discount_expire_at_jalali" id="discount_expire_at_jalali" class="form-control" value="{{ old('discount_expire_at_jalali') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">توضیحات</label>
                                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 w-100">ثبت</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
