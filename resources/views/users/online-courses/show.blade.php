@extends('users.layouts.master')

@section('content')
    <style>
        .card-body {
            padding: 10px !important;
        }

        .container-xxl {
            padding: 10px !important;
        }

        @media (max-width: 768px) {
            .banner {
                height: 60px !important;
            }
        }

        @media (min-width: 768px) {
            .banner {
                height: 130px !important;
            }
        }

        .banner {
            width: 100%;
            overflow: hidden;
            padding: 10px !important;
            border-radius: 5px;
            background-color: #ffffff;
            margin: 10px 0px;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
        }

        .banner img {
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y ">
        @if ($discount and mb_strlen($discount->banner) > 0)
            <div class="banner">
                <img src="{{ asset('public/images/discounts/banners/' . $discount->banner) }}" class="rounded discount_banner" onclick="copy('{{ $discount->code }}')">
            </div>

            <div class="d-grid w-100 mt-3 pt-2">
                <span class="text-center d-none alert alert-success copied_discount_code"><strong>کپی شد</strong></span>
                <script>
                    function copy(code) {
                        navigator.clipboard.writeText(code);
                        $('span.copied_discount_code').removeClass('d-none');
                        $('span.copied_discount_code').fadeIn(100);
                        setTimeout(() => {
                            $('span.copied_discount_code').fadeOut(300);
                        }, 1500);
                    }
                </script>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class=" h5 mb-4">{{ $onlineCourse->name }}</h5>
                        <a href="{{ route('user.online-courses.index') }}" class="btn btn-primary mb-4 btn-sm"> <i class="bx bx-arrow-back"></i> بازگشت </a>
                    </div>
                    @include('users.layouts.alerts')
                    <div class="col-md-12">
                        <div class="alert alert-info  text-center  mb-4">
                            <h5 class="text-info m-0">با خرید این دوره کیف پول شما
                                <span class=""> {{ number_format($onlineCourse->amount * 0.1) }}</span>
                                تومان شارژ خواهد شد
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mt-4">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bx bx-time"></i> مدت زمان دوره : {{ $onlineCourse->duration_hour }} ساعت </li>
                                <li class="mb-2"><i class="bx bx-user"></i> استاد : {{ $onlineCourse->teacher?->user->full_name ?? '--' }} </li>
                            </ul>
                            <h5 class="mt-4">توضیحات دوره:</h5>
                            <p>{{ $onlineCourse->description }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">ثبت نام در دوره</h5>
                                @if ($onlineCourse->discount_amount > 0 && intval($onlineCourse->discount_start_at) <= time() && intval($onlineCourse->discount_expire_at) >= time())
                                    <div class="text-center mb-3">
                                        <p class="mb-2">زمان باقی مانده تا پایان تخفیف:</p>
                                        <div class="countdown d-flex justify-content-center gap-2" data-expire="{{ $onlineCourse->discount_expire_at }}">
                                            <div>
                                                <span class="seconds bg-primary text-white px-2 py-1 rounded">00</span>
                                                <div class="small mt-2">ثانیه</div>
                                            </div>
                                            <div>
                                                <span class="minutes bg-primary text-white px-2 py-1 rounded">00</span>
                                                <div class="small mt-2">دقیقه</div>
                                            </div>
                                            <div>
                                                <span class="hours bg-primary text-white px-2 py-1 rounded">00</span>
                                                <div class="small mt-2">ساعت</div>
                                            </div>
                                            <div>
                                                <span class="days bg-primary text-white px-2 py-1 rounded">00</span>
                                                <div class="small mt-2">روز</div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const countdownEl = document.querySelector('.countdown');
                                            const expireTime = parseInt(countdownEl.dataset.expire) * 1000;

                                            function updateCountdown() {
                                                const now = new Date().getTime();
                                                const distance = expireTime - now;

                                                if (distance < 0) {
                                                    location.reload();
                                                    return;
                                                }

                                                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                                countdownEl.querySelector('.days').textContent = String(days).padStart(2, '0');
                                                countdownEl.querySelector('.hours').textContent = String(hours).padStart(2, '0');
                                                countdownEl.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
                                                countdownEl.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
                                            }

                                            updateCountdown();
                                            setInterval(updateCountdown, 1000);
                                        });
                                    </script>
                                @endif
                                <div class="text-center mb-4">
                                    @if ($onlineCourse->discount_amount > 0 && intval($onlineCourse->discount_start_at) <= time() && intval($onlineCourse->discount_expire_at) >= time())
                                        <del class="text-muted">{{ number_format($onlineCourse->amount) }} تومان</del>
                                        <div class="h5 text-success">{{ number_format($onlineCourse->discount_amount) }} تومان</div>
                                    @else
                                        <span class="h5">با فقط </span>
                                        <div class="h5">{{ number_format($onlineCourse->amount) }} تومان</div>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    {{-- <a href="{{ route('user.online-courses.register', $onlineCourse->id) }}" class="btn btn-success w-100">ثبت نام در دوره</a> --}}
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('user.online-courses.add-to-cart', $onlineCourse->id) }}" class="btn btn-primary w-100">افزودن به سبد خرید</a>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('user.online-course-baskets.index') }}" class="btn btn-info w-100">مشاهده سبد خرید</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3>دوره های مشابه</h3>
                        <div class="row">
                            @forelse ($sameOnlineCourses as $sameOnlineCourse)
                                <div class="col-md-4 mb-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">{{ $sameOnlineCourse->name }}</h5>
                                            <ul class="list-unstyled text-center">
                                                <li class="mb-2"><i class="bx bx-time"></i> مدت زمان دوره : {{ $sameOnlineCourse->duration_hour }} ساعت </li>
                                                <li class="mb-2"><i class="bx bx-user"></i> استاد : {{ $sameOnlineCourse->teacher?->user->full_name ?? '--' }} </li>
                                            </ul>
                                            <div class="text-center mb-4">
                                                @if ($sameOnlineCourse->discount_amount > 0 && intval($sameOnlineCourse->discount_start_at) <= time() && intval($sameOnlineCourse->discount_expire_at) >= time())
                                                    <del class="text-muted">{{ number_format($sameOnlineCourse->amount) }} تومان</del>
                                                    <div class="h5 text-success">{{ number_format($sameOnlineCourse->discount_amount) }} تومان</div>
                                                @else
                                                    <div class="h5">{{ number_format($sameOnlineCourse->amount) }} تومان</div>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <a href="{{ route('user.online-courses.show', $sameOnlineCourse->id) }}" class="btn btn-info w-100 mb-2">مشاهده دوره</a>
                                                <a href="{{ route('user.online-courses.add-to-cart', $sameOnlineCourse->id) }}" class="btn btn-primary w-100 mb-2">افزودن به سبد خرید</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12">
                                    <div class="alert alert-info text-center">دوره مشابهی یافت نشد!</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
