@extends('users.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-3">
            <a href="{{ route('user.courses.index') }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-arrow-right me-2"></i>
                بازگشت به لیست دوره‌ها
            </a>
        </div>
        <div class="card">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <h4 class="card-title mb-0">{{ $course->title }}</h4>
                <span class="badge bg-primary">کلاس : {{ $course->course_type == 'private' ? 'خصوصی' : ($course->course_type == 'public' ? 'عمومی' : 'نیمه خصوصی') }}</span>
            </div>

            <div class="col-md-12">
                <div class="alert alert-info  text-center  mb-4">
                    <h5 class="text-info m-0">با پرداخت کامل این دوره کیف پول شما
                        <span class=""> {{ number_format(($course->price - $course->price * 0.05) * 0.1) }}</span>
                        تومان شارژ خواهد شد
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-users me-2 text-primary"></i>
                            <div>
                                <small class="text-muted d-block">ظرفیت دوره</small>
                                <strong>{{ $course->capacity }} نفر</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-money-bill me-2 text-success"></i>
                            <div>
                                <small class="text-muted d-block">شهریه دوره</small>
                                <strong>{{ number_format($course->price) }} تومان</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-calendar me-2 text-info"></i>
                            <div>
                                <small class="text-muted d-block">تاریخ شروع و پایان</small>
                                <strong>{{ verta($course->start_date)->format('Y/m/d') }} تا {{ verta($course->end_date)->format('Y/m/d') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-clock me-2 text-warning"></i>
                            <div>
                                <small class="text-muted d-block">ساعت برگزاری</small>
                                <strong>{{ $course->start_time }} تا {{ $course->end_time }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-hourglass me-2 text-danger"></i>
                            <div>
                                <small class="text-muted d-block">مدت دوره</small>
                                <strong>{{ $course->duration_hours }} ساعت</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-calendar-day me-2 text-primary"></i>
                            <div>
                                <small class="text-muted d-block">روزهای برگزاری</small>
                                <strong>{{ implode(
                                    '، ',
                                    array_map(function ($day) {
                                        $days = [
                                            '0' => 'شنبه',
                                            '1' => 'یکشنبه',
                                            '2' => 'دوشنبه',
                                            '3' => 'سه‌شنبه',
                                            '4' => 'چهارشنبه',
                                            '5' => 'پنج‌شنبه',
                                            '6' => 'جمعه',
                                        ];
                                        return $days[$day] ?? $day;
                                    }, $course->week_days),
                                ) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $capacity = $course->capacity - $course->courseRegisters->count();
                @endphp
                @if ($capacity > 0)
                    <livewire:users.courses.show :course="$course" />
                @else
                    <div class="alert alert-danger text-center mt-5">
                        ظرفیت تکمیل شده است
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
