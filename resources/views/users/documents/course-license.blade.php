@extends('users.layouts.master')

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6 p-1">
                <div class="card pb-3">
                    <div class="card-body">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h4 class="h4   text-gray-800">دوره های حضوری من</h4>
                        </div>
                        @include('users.layouts.alerts')
                        <div class="row">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr class="font-14 text-center">
                                        <th>عنوان دوره</th>
                                        <th>تاریخ پایان</th>
                                        <th class="text-center">مشاهده مدرک</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($userCourseRegisters as $index => $userCourseRegister)
                                        <tr class="font-14 text-center">
                                            <td>
                                                <div class="d-flex align-items-center"> <span>{{ $userCourseRegister->course->title }}</span></div>
                                            </td>
                                            <td><span>{{ georgianToJalali($userCourseRegister->course->end_date) }}</span></td>
                                            <td>
                                                @if ($userCourseRegister->course->end_date >= now())
                                                    <span class="text-warning"> دوره هنوز تمام نشده</span>
                                                @else
                                                    <a href="{{ route('user.documents.course-license-show', $userCourseRegister->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye me-1"></i>مشاهده مدرک
                                                    </a>
                                                @endIf
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center p-3">
                                                <div class="text-muted">
                                                    <i class="fa fa-info-circle fa-2x mb-2"></i>
                                                    <p>هیچ دوره‌ای یافت نشد!</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 p-1">
                <div class="card pb-3">
                    <div class="card-body">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h4 class="h4   text-gray-800">دوره های آنلاین من</h4>
                        </div>
                        @include('users.layouts.alerts')
                        <div class="row">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr class="font-14 text-center">
                                        <th>عنوان دوره</th>
                                        <th class="text-center">مشاهده مدرک</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orderItems as $index => $orderItem)
                                        <tr class="font-14 text-center">
                                            <td>
                                                <div class="text-center">{{ $orderItem->onlineCourse->name }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('user.documents.online-course-license-show', $orderItem->id) }}" class="btn btn-sm btn-primary ">
                                                    <i class="fa fa-eye me-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center p-3">
                                                <div class="text-muted">
                                                    <i class="fa fa-info-circle fa-2x mb-2"></i>
                                                    <p>هیچ دوره‌ای یافت نشد!</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
