<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-fluid">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">

                <!-- Style Switcher -->
                <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                        <i class="bx bx-sm"></i>
                    </a>
                </li>
                <!--/ Style Switcher -->


                {{-- user --}}
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ user()?->student?->personal_image ? GetImage('students/personal/'.user()->student->personal_image) : asset('admin-panel/assets/img/logo/logo.jfif')    }}" alt class="rounded-circle">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">
                                            @if (Auth::user())
                                                {{ Auth::user()->fullName }}
                                            @endif
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
                            <a class="dropdown-item" href="">
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
                <!--/ User -->
            </ul>
        </div>
    </div>
</nav>
@include('users.layouts.AI')
