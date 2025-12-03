<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link p-2">
            <span class="app-brand-logo demo">
            </span>
            <img src="{{ asset('admin-panel/assets/img/logo/logo.jfif') }}" alt="" class="img-thumbnail" style="width: 35px;">
            <span class="app-brand-text demo menu-text fw-bold ms-2">
                {{ __('public.company_name') }}
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item {{ ActiveMenu('user.profile.index') }}">
            <a href="{{ route('user.profile.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>{{ __('sidebar.profile') }}</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">آموزشی</span></li>
        <li class="menu-item {{ ActiveMenu('user.courses.index') }}">
            <a href="{{ route('user.courses.index') }}" class="menu-link">
                <i class='menu-icon tf-icons fa-solid fa-building me-2'></i>
                <div>دوره های حضوری</div>
            </a>
        </li>
        <li class="menu-item {{ ActiveMenu('user.online-courses.index') }}">
            <a href="{{ route('user.online-courses.index') }}" class="menu-link">
                <i class='menu-icon tf-icons fa-solid fa-list me-2'></i>
                <div>دوره های آنلاین</div>
            </a>
        </li>
        <li class="menu-item {{ ActiveMenu('user.my-online-courses.index') }}">
            <a href="{{ route('user.my-online-courses.index') }}" class="menu-link">
                <i class='menu-icon tf-icons fa fa-tv me-2'></i>
                <div>دوره های آنلاین من</div>
            </a>
        </li>
        <li class="menu-item {{ ActiveMenu('user.orders.index') }}">
            <a href="{{ route('user.orders.index') }}" class="menu-link">
                <i class='menu-icon tf-icons fa fa-box-open me-2'></i>
                <div>سفارش های من</div>
            </a>
        </li>
        <li class="menu-item {{ ActiveMenu('user.payments.index') }}">
            <a href="{{ route('user.payments.index') }}" class="menu-link">
                <i class='menu-icon tf-icons fa fa-credit-card me-2'></i>
                <div>پرداخت های من</div>
            </a>
        </li>
        <li class="menu-item {{ ActiveMenu('user.gifts.index') }}">
            <a href="{{ route('user.gifts.index') }}" class="menu-link">
                <i class='menu-icon tf-icons fa fa-gift me-2'></i>
                <div>هدایا</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase"><span class="menu-header-text">سبد خرید</span></li>
        <li class="menu-item {{ ActiveMenu('user.online-course-baskets.index') }}">
            <a href="{{ route('user.online-course-baskets.index') }}" class="menu-link">
                <i class='menu-icon tf-icons fa-solid fa-cart-shopping me-2'></i>
                <div>سبد دوره آنلاین</div>
            </a>
        </li>
        <li class="menu-item {{ ActiveMenu('user.course-baskets.index') }}">
            <a href="{{ route('user.course-baskets.index') }}" class="menu-link">
                <i class='menu-icon tf-icons fa-solid fa-cart-shopping me-2'></i>
                <div>سبد دوره حضوری</div>
            </a>
        </li>
        @if (user()->student)
            <li class="menu-header small text-uppercase"><span class="menu-header-text">مدارک</span></li>
            <li class="menu-item {{ ActiveMenu('user.documents.course-license') }}">
                <a href="{{ route('user.documents.course-license') }}" class="menu-link">
                    <i class='menu-icon tf-icons fa fa-file-image   me-2'></i>
                    <div>گواهی پایان دوره</div>
                </a>
            </li>
            <li class="menu-item {{ ActiveMenu('user.documents.identity-upload') }}">
                <a href="{{ route('user.documents.identity-upload') }}" class="menu-link">
                    <i class='menu-icon tf-icons fa fa-id-card   me-2'></i>
                    <div>بارگذاری مدارک هویتی</div>
                </a>
            </li>
            <li class="menu-item {{ ActiveMenu('user.exams.index') }}">
                <a href="{{ route('user.exams.index') }}" class="menu-link">
                    <i class='menu-icon tf-icons fa fa-list-1-2   me-2'></i>
                    <div>آزمون کتبی</div>
                </a>
            </li>
            @if (user()->wallet > 0)
                <li class="menu-item {{ ActiveMenu('user.wallet') }}">
                    <a href="{{ route('user.wallet') }}" class="menu-link">
                        <i class='menu-icon tf-icons fa fa-wallet   me-2'></i>
                        <div>کیف پول</div>
                    </a>
                </li>
            @endIf
            <li class="menu-item {{ ActiveMenu('user.reference') }}">
                <a href="{{ route('user.reference') }}" class="menu-link">
                    <i class='menu-icon tf-icons fa fa-refresh   me-2'></i>
                    <div>کد معرف</div>
                </a>
            </li>
            <li class="menu-item {{ ActiveMenu('user.resume.index') }}">
                <a href="{{ route('user.resume.index') }}" class="menu-link">
                    <i class='menu-icon tf-icons fa fa-file-alt   me-2'></i>
                    <div>رزومه ساز</div>
                </a>
            </li>
        @endIf
        <hr class="mt-2 mb-2">
        <li class="menu-item ">
            <a href="{{ route('auth.logout') }}" class="menu-link">
                <i class='menu-icon fa fa-power-off   me-2'></i>
                <div>خروج</div>
            </a>
        </li>
    </ul>
</aside>
