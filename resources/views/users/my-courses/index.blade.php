@extends('users.layouts.master')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="h4   text-gray-800">دوره های آنلاین من</h4>
            </div>
            <div class="alert alert-info mb-5">
                <h6 class="text-center text-gray-800 m-0 font-16">
                    برای مشاهده دوره‌ها ابتدا پلیر را با توجه به سیستم عامل خود در انتهای همین صفحه دانلود و نصب نمایید. پس از اجرای پلیر، در صفحه ثبت دوره جدید کلید لایسنس را وارد، مکان ذخیره‌سازی را
                    انتخاب و سپس فرم را
                    تایید کنید.
                </h6>
            </div>
            <div class="alert alert-warning mb-5">
                <h5 class="text-center text-danger m-0">مطالب کلیه دوره ها دارای واترمارک‌های پیدا و پنهان هستند و هر گونه کپی برداری و نشر آن قابل پیگیری بوده و موجب پیگرد قانونی خواهد شد.</h5>
            </div>
            <div class="row">
                @foreach ($orderItem as $item)
                    <div class="col-xl-4 col-lg-5 col-md-5  mb-3 ">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-block text-center">
                                    <div class="d-block   text-center">
                                        <sup class="h5 pricing-currency mt-1 mt-sm-4 mb-2 me-1 text-primary text-center">{{ $item->onlineCourse->name }}</sup>
                                    </div>
                                </div>
                                <ul class="ps-3 g-2 mb-3 lh-1-85 ">
                                    <li class="mb-2">مدت زمان دوره : <span class="text-primary">{{ $item->onlineCourse->duration_hour }} ساعت</span></li>
                                    <li class="mb-2">استاد : <span class="text-primary">{{ $item->onlineCourse?->teacher?->user->full_name }}</span></li>
                                    <li class="mb-2">تاریخ خرید : <span class="text-primary">{{ verta($item->created_at)->format('Y/m/d') }}</span></li>
                                    <li class="mb-2 d-none">لایسنس : <span class="text-primary license_key_{{$item->id}}" data-lisence="{{ $item->license_key }}"> </span></li>
                                </ul>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">اعتبار لایسنس</h6>
                                    <h6 class="mb-0">مادام العمر</h6>
                                </div>
                                <div class="progress mb-3" style="height: 8px">
                                    <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <ul class="mb-3 d-inline-flex gap-md-5 gap-sm-4  gap-4 ">
                                    <li class="text-muted">1 ماه</li>
                                    <li class="text-muted">3 ماه</li>
                                    <li class="text-muted">6 ماه</li>
                                    <li class="text-muted">1 سال</li>
                                    <li class="text-primary">مادام العمر</li>
                                </ul>
                                <div class="d-grid w-100 mt-3 pt-2">
                                    <span class="text-center d-none alert alert-success copy_license_key_{{$item->id}}"><strong>کپی شد</strong></span>
                                    <button class="btn btn-primary" onclick="copy('license_key_{{$item->id}}')">
                                        کپی لایسنس
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    function copy(item) {
        var lisence = $('.' + item).attr('data-lisence');
        navigator.clipboard.writeText(lisence);
        $('span.copy_' + item).removeClass('d-none');
        $('span.copy_' + item).fadeIn(100);
        setTimeout(() => {
            $('span.copy_' + item).fadeOut(300);
        }, 1500);

    }
</script>

<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <div class="alert alert-primary mb-5 text-center  ">
                <span class="font-19 h4">
                    دانلود اسپات پلیر برای مشاهده دوره ها
                </span>
            </div>
            <style>
                .download-box {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    background: #f7f1ff;
                    border-radius: 5px;
                    padding: 10px 10px 5px;
                }

                .download-box a {
                    flex-basis: 0;
                    flex-grow: 1;
                    min-width: 100px;
                    text-align: center;
                    padding: 10px;
                }

                .download-box a b {

                    display: block;
                    font-weight: 700;
                    font-size: 14px;
                }

                .download-box a u {
                    color: #666;
                    font-size: 12px;
                    text-decoration: none;

                }

                .download-box a img {
                    width: 80px;
                    border-radius: 10px;
                    display: inline-block;
                    margin-bottom: 5px;
                }
            </style>
            <div class="   download-box">
                <a target="_blank" href="https://app.newdeniz.com/assets/bin/newdeniz/setup.exe" class=""> <img decoding="async" alt="Windows"
                        src="https://app.newdeniz.com/assets/img/platform/win.png"><b>Windows</b> <u>5.3.2.32</u>
                </a>
                <a target="_blank" href="https://app.newdeniz.com/assets/bin/newdeniz/setup.dmg" class=""> <img decoding="async" alt="MacOS"
                        src="https://app.newdeniz.com/assets/img/platform/mac.png"><b>MacOS</b> <u>5.3.2.32</u>
                </a>
                <a target="_blank" class=""> <img decoding="async" alt="Ubuntu" src="https://app.newdeniz.com/assets/img/platform/ubuntu.png">
                    <b>Ubuntu</b><u>به زودی</u>
                </a>
                <a target="_blank" href="https://app.newdeniz.com/assets/bin/newdeniz/setup.apk" class=""> <img decoding="async" alt="Android"
                        src="https://app.newdeniz.com/assets/img/platform/android.png"><b>Android</b> <u>5.2.0.28</u>
                </a>
                <a target="_blank" class=""> <img decoding="async" alt="iOS" src="https://app.newdeniz.com/assets/img/platform/ios.png">
                    <b>iOS</b> <u>به زودی</u>
                </a>
                <a target="_blank" href="https://app.newdeniz.com/" class=""> <img decoding="async" alt="Web" src="https://app.newdeniz.com/assets/img/platform/web.png"> <b>Web</b>
                    <u>5.0.3.24</u>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
