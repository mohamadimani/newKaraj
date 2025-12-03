@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <span>لیست دوره های موجود در سبد خرید</span>
                            <span class="text-info">{{ $user->full_name }}</span>
                        </h5>
                        <a href="{{ route('online-course-baskets.index') }}" class="btn btn-primary btn-sm">بازگشت</a>
                    </div>
                    <hr>
                    <div class="card-body">
                        <form action="{{ route('online-course-baskets.store', $user) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label font-16">ثبت دوره آنلاین</label>
                                    <select name="online_course_id" class="form-select select2">
                                        <option value="">انتخاب کنید</option>
                                        @foreach ($onlineCourses as $onlineCourse)
                                            <option value="{{ $onlineCourse->id }}">{{ $onlineCourse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3 ">
                                    <label class="form-label font-16"> &nbsp; </label>
                                    <button type="submit" class="btn btn-primary d-block"> ثبت </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <div class="table-responsive text-nowrap p-2  ">
                        @include('users.layouts.alerts')
                        @if ($onlineCourseBaskets->count() > 0)
                                            <table class="table table-hover table-striped table-bordered">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>ردیف</th>
                                                        <th>نام دوره</th>
                                                        <th>شهریه (تومان)</th>
                                                        <th>تاریخ ثبت</th>
                                                        <th>عملیات</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0">
                                                    @foreach ($onlineCourseBaskets as $onlineCourseBasket)
                                                                            <tr class="text-center">
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>{{ $onlineCourseBasket->onlineCourse->name }}</td>
                                                                                @if (
                                                                                    $onlineCourseBasket->onlineCourse->discount_amount > 0 &&
                                                                                    intval($onlineCourseBasket->onlineCourse->discount_start_at) <= time() &&
                                                                                    intval($onlineCourseBasket->onlineCourse->discount_expire_at) >= time()
                                                                                )
                                                                                                            <td>{{ number_format($onlineCourseBasket->onlineCourse->discount_amount) }}</td>
                                                                                @else
                                                                                    <td>{{ number_format($onlineCourseBasket->onlineCourse->amount) }}</td>
                                                                                @endif
                                                                                <td>{{ verta($onlineCourseBasket->created_at)->format('Y-m-d H:i:s') }}</td>
                                                                                <td>
                                                                                    <form action="{{ route('online-course-baskets.destroy', $onlineCourseBasket) }}" method="post">
                                                                                        @csrf
                                                                                        @method('delete')
                                                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bx bx-trash me-1"></i> حذف</button>
                                                                                    </form>
                                                                                </td>
                                                                            </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="5"></td>
                                                    </tr>
                                                    <tr class="text-center ">
                                                        <td colspan="2" class="fw-bold">جمع کل:</td>
                                                        <td>{{ number_format($onlineCourseBaskets->sum(function ($basket) {
                                return $basket->onlineCourse->discount_amount > 0 &&
                                    intval($basket->onlineCourse->discount_start_at) <= time() &&
                                    intval($basket->onlineCourse->discount_expire_at) >= time()
                                    ? $basket->onlineCourse->discount_amount
                                    : $basket->onlineCourse->amount;
                            }))}} تومان</td>
                                                        <td colspan="2">
                                                            <a href="{{ route('online-course-orders.store', $user->id) }}" type="submit" class="btn btn-success w-100 ">ثبت سفارش</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                        @else
                            <div class="text-center alert alert-info">
                                <span class="fw-bold font-16">سبد خرید خالی است</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
