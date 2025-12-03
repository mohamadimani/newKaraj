@php
    $secretaries = \App\Models\SalesTeamSecretary::get();
@endphp
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-fluid">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>
        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav d-flex flex-row align-items-center ms-auto col-md-12">
                <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                        <i class="bx bx-sm"></i>
                    </a>
                </li>
                <li class="nav-item w-100">
                    <div class="scrolling-text-container">
                        @php
                            $monthSaleSum = 0;
                            $dailySaleSum = 0;
                        @endphp
                        @foreach ($secretaries as $secretary)
                            @php
                                $lastMonthSales = $secretary->secretary->lastMonthSales();
                                $todaySales = $secretary->secretary->todaySales();

                                $monthSaleSum = $monthSaleSum + $lastMonthSales;
                                $dailySaleSum = $dailySaleSum + $todaySales;
                            @endphp
                            <div class="scrolling-text bg-label-primary p-1">
                                <span class="font-10  d-block"> {{ $secretary->secretary->user->full_name }}</span>
                                <small class="text-dark font-10 d-block" data-secretary-id="{{ $secretary->secretary->id }}" data-last-month-sales="{{ $lastMonthSales }}">
                                    فروش ماهانه: {{ $lastMonthSales }}
                                </small>
                                <small class="text-dark font-10 d-block text" data-secretary-id="{{ $secretary->secretary->id }}" data-today-sales="{{ $todaySales }}">
                                    فروش روزانه: {{ $todaySales }}
                                </small>
                            </div>
                        @endforeach
                        <div class="scrolling-text bg-label-primary p-1">
                            <span class="font-10  d-block">جمع کل : </span>
                            <small class="text-dark font-10 d-block"> ماهانه: {{ $monthSaleSum }} </small>
                            <small class="text-dark font-10 d-block "> روزانه: {{ $dailySaleSum }} </small>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            $('.scrolling-text small').each(function() {
                                var lastMonthSales = $(this).data('last-month-sales');
                                var secretaryId = $(this).data('secretary-id');
                                // Find max sales value among all secretaries
                                var maxSales = Math.max.apply(null, $('.scrolling-text small').map(function() {
                                    return $(this).data('last-month-sales');
                                }).get());
                                // If this secretary has max sales, change background color
                                if (lastMonthSales === maxSales) {
                                    $(this).closest('.scrolling-text')
                                        .removeClass('bg-label-primary')
                                        .addClass('bg-label-success');
                                }
                                // Find min sales value among all secretaries
                                var minSales = Math.min.apply(null, $('.scrolling-text small').map(function() {
                                    return $(this).data('last-month-sales');
                                }).get());
                                // If this secretary has min sales, change background color to danger
                                if (lastMonthSales === minSales) {
                                    $(this).closest('.scrolling-text')
                                        .removeClass('bg-label-primary')
                                        .addClass('bg-label-danger');
                                }
                            });
                        });
                    </script>
                    <style>
                        .scrolling-text-container {
                            white-space: nowrap;
                            width: 100%;
                            position: relative;
                            overflow: hidden;
                        }

                        .scrolling-text {
                            display: inline-block;
                            padding-right: 0;
                        }
                    </style>
                </li>
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="bx bx-grid-alt bx-sm"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto secondary-font">میانبرها</h5>
                            </div>
                        </div>
                        <div class="dropdown-shortcuts-list scrollable-container">
                            <div class="row row-bordered overflow-visible g-0">
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                        <i class="bx bx-user-plus fs-4"></i>
                                    </span>
                                    <a href="{{ route('clues.create') }}" class="stretched-link">ثبت سرنخ</a>
                                </div>
                                <div class="dropdown-shortcuts-item col">
                                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                        <i class="bx bx-book fs-4"></i>
                                    </span>
                                    <a href="{{ route('courses.create') }}" class="stretched-link">ثبت دوره</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                {{-- user --}}
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ asset('admin-panel/assets/img/logo/logo.jfif') }} " alt class="rounded-circle">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block ">
                                            <i class="bx bx-user-circle me-2"></i>
                                            {{ Auth::user()->fullName }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @if (session()->get('last_login_user_id') and session()->get('last_login_user_id') != Auth::id())
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('auth.login-by-user-id', session()->get('last_login_user_id')) }}">
                                    <i class="bx bx-log-in me-2"></i>
                                    <span class="align-middle">بازگشت به پروفایل قبلی</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bx bx-user me-2"></i>
                                <span class="align-middle">پروفایل من</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('auth.logout') }}">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">{{ __('public.logout') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
