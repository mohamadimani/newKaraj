@php
    use Illuminate\Support\Facades\Auth;
    use App\Constants\PermissionTitle;
@endphp
<style>
    .menu-inner .menu-item .menu-link {
        padding: 7px !important;
    }

    .text-uppercase {
        margin-top: 10px !important;
    }
</style>
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
        <li class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>{{ __('sidebar.dashboard') }}</div>
            </a>
        </li>
        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_CLUE) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_GROUP_DESCRIPTION) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE_REGISTER))
            <li class="menu-header small text-uppercase"><span class="menu-header-text">حضوری</span></li>
        @endif
        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_GROUP_DESCRIPTION))
            <li class="menu-item {{ ActiveMenu('group-descriptions.index') }}">
                <a href="{{ route('group-descriptions.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-list-ul me-2"></i>
                    <div>اطلاعات حرفه ها</div>
                </a>
            </li>
        @endif
        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_CLUE))
            <li class="menu-item {{ ActiveMenu('clues.index') }}">
                <a href="{{ route('clues.index') }}" class="menu-link">
                    <i class='menu-icon tf-icons bx bx-shape-square'></i>
                    <div>سرنخ ها</div>
                </a>
            </li>
        @endif
        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE_REGISTER))
            <li class="menu-item {{ ActiveMenu('course-registers.index') }}">
                <a href="{{ route('course-registers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-rectangle-list"></i>
                    <div>ثبت نام ها</div>
                </a>
            </li>
        @endif
        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_STUDENT))
            <li class="menu-item {{ ActiveMenu('students.index') }}">
                <a href="{{ route('students.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons fa-solid fa-user-graduate"></i>
                    <div>{{ __('students.page_title') }}</div>
                </a>
            </li>
        @endif
        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE_RESERVE) or
                Auth::user()->hasPermissionTo(PermissionTitle::CANCEL_COURSE_REGISTER) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_CLASS_ROOM) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_PROFESSION) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_FOLLOW_UP))
            <li class="menu-header small text-uppercase"><span class="menu-header-text"> </span></li>
            <li
                class="menu-item  {{ OpenMenu('course-reserves.index') .
                    ' ' .
                    OpenMenu('course-cancels.index') .
                    ' ' .
                    OpenMenu('class-rooms.index') .
                    ' ' .
                    OpenMenu('professions.index') .
                    ' ' .
                    OpenMenu('courses.index') .
                    ' ' .
                    OpenMenu('follow-ups.index') }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-message-square-detail"></i>
                    <div>دوره های حضوری</div>
                </a>
                <ul class="menu-sub remove-padding">
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE_RESERVE))
                        <li class="menu-item {{ ActiveMenu('course-reserves.index') }}">
                            <a href="{{ route('course-reserves.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                                <div>رزرو ها</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::CANCEL_COURSE_REGISTER))
                        <li class="menu-item {{ ActiveMenu('course-cancels.index') }}">
                            <a href="{{ route('course-cancels.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                                <div>انصرافی ها</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_CLASS_ROOM))
                        <li class="menu-item {{ ActiveMenu('class-rooms.index') }}">
                            <a href="{{ route('class-rooms.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-chalkboard"></i>
                                <div>کلاس ها</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_PROFESSION))
                        <li class="menu-item {{ ActiveMenu('professions.index') }}">
                            <a href="{{ route('professions.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-briefcase"></i>
                                <div>حرفه ها</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE))
                        <li class="menu-item {{ ActiveMenu('courses.index') }}">
                            <a href="{{ route('courses.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-book"></i>
                                <div>دوره ها</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_FOLLOW_UP))
                        <li class="menu-item {{ ActiveMenu('follow-ups.index') }}">
                            <a href="{{ route('follow-ups.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-timer"></i>
                                <div>پیگیری ها</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_ORDER) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_BASKET) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE) or
                Auth::user()->hasPermissionTo(PermissionTitle::CREATE_ONLINE_COURSE) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_GROUP) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE))
            <li class="menu-header small text-uppercase"><span class="menu-header-text">آنلاین</span></li>
            <li
                class="menu-item  {{ OpenMenu('online-courses.index') .
                    ' ' .
                    OpenMenu('online-courses.create') .
                    ' ' .
                    OpenMenu('online-course-groups.index') .
                    ' ' .
                    OpenMenu('online-course-baskets.index') .
                    ' ' .
                    OpenMenu('online-course-orders.registers') .
                    ' ' .
                    OpenMenu('online-courses.sms_marketing') .
                    ' ' .
                    OpenMenu('online-course-orders.index') }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-message-square-detail"></i>
                    <div>دوره های آنلاین</div>
                </a>
                <ul class="menu-sub remove-padding">
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_ORDER))
                        <li class="menu-item {{ ActiveMenu('online-course-orders.index') }}">
                            <a href="{{ route('online-course-orders.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-box-open me-2"></i>
                                <div>سفارش ها</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_ORDER))
                        <li class="menu-item {{ ActiveMenu('online-course-orders.registers') }}">
                            <a href="{{ route('online-course-orders.registers') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-box-open me-2"></i>
                                <div>ثبت نام های آنلاین</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_BASKET))
                        <li class="menu-item {{ ActiveMenu('online-course-baskets.index') }}">
                            <a href="{{ route('online-course-baskets.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-cart-shopping me-2"></i>
                                <div>سبد خرید</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE))
                        <li class="menu-item {{ ActiveMenu('online-courses.index') }}">
                            <a href="{{ route('online-courses.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-list me-2"></i>
                                <div>لیست دوره های آنلاین</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::CREATE_ONLINE_COURSE))
                        <li class="menu-item {{ ActiveMenu('online-courses.create') }}">
                            <a href="{{ route('online-courses.create') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-plus me-2"></i>
                                <div>افزودن دوره آنلاین</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_GROUP))
                        <li class="menu-item {{ ActiveMenu('online-course-groups.index') }}">
                            <a href="{{ route('online-course-groups.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-layer-group me-2"></i>
                                <div>گروه بندی دوره های آنلاین</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE))
                        <li class="menu-item {{ ActiveMenu('online-courses.sms_marketing') }}">
                            <a href="{{ route('online-courses.sms_marketing') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-message-dots me-2"></i>
                                <div>پیامک زماندار آنلاین</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_COURSE_PAYMENT) or Auth::user()->hasPermissionTo(PermissionTitle::INDEX_PAYMENT))
            <li class="menu-header small text-uppercase"><span class="menu-header-text">مالی</span></li>
            <li
                class="menu-item {{ OpenMenu('payments.index') .
                    ' ' .
                    OpenMenu('course-payments.index') .
                    ' ' .
                    OpenMenu('refund.index') .
                    ' ' .
                    OpenMenu('online-course-payments.index') .
                    ' ' .
                    OpenMenu('discounts.index') .
                    ' ' .
                    OpenMenu('payment-methods.index') .
                    ' ' .
                    OpenMenu('online-course-percentages.index') }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-briefcase"></i>
                    <div>مالی</div>
                </a>
                <ul class="menu-sub remove-padding">
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_PAYMENT))
                        <li class="menu-item {{ ActiveMenu('payments.index') }}">
                            <a href="{{ route('payments.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-money-bill me-2"></i>
                                <div>پرداخت ها</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REFUND_PAYMENT))
                        <li class="menu-item {{ ActiveMenu('refund.index') }}">
                            <a href="{{ route('refund.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-refresh me-2"></i>
                                <div>عودت وجه</div>
                            </a>
                        </li>
                    @endif
                    @can(PermissionTitle::INDEX_COURSE_PAYMENT)
                        <li class="menu-item {{ ActiveMenu('course-payments.index') }}">
                            <a href="{{ route('course-payments.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-credit-card me-2"></i>
                                <div>پرداخت آنلاین (دوره حضوری)</div>
                            </a>
                        </li>
                    @endcan
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_PAYMENT))
                        <li class="menu-item {{ ActiveMenu('online-course-payments.index') }}">
                            <a href="{{ route('online-course-payments.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-money-bill me-2"></i>
                                <div>پرداخت های آنلاین</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ONLINE_COURSE_PERCENTAGE))
                        <li class="menu-item {{ ActiveMenu('online-course-percentages.index') }}">
                            <a href="{{ route('online-course-percentages.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-percent me-2"></i>
                                <div>پورسانت دوره های آنلاین</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_PAYMENT_METHOD))
                        <li class="menu-item {{ ActiveMenu('payment-methods.index') }}">
                            <a href="{{ route('payment-methods.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-money-bill me-2"></i>
                                <div>{{ __('sidebar.payment-methods') }}</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_DISCOUNT))
                        <li class="menu-item {{ ActiveMenu('discounts.index') }}">
                            <a href="{{ route('discounts.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-percent me-2"></i>
                                <div>{{ __('discounts.page_title') }}</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <li class="menu-header small text-uppercase"><span class="menu-header-text">مدیریتی</span></li>
        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_MARKETING_SMS_TEMPLATE))
            <li class="menu-item  {{ OpenMenu('marketing-sms-templates.index') }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-message-square-detail"></i>
                    <div>{{ __('public.sms_settings') }}</div>
                </a>
                <ul class="menu-sub remove-padding">
                    <li class="menu-item {{ ActiveMenu('marketing-sms-templates.index') }}">
                        <a href="{{ route('marketing-sms-templates.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-message-dots me-2"></i>
                            <div>{{ __('marketing_sms_templates.page_title_single') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_TECHNICAL))
            <li class="menu-item {{ OpenMenu('technicals.index') . ' ' . OpenMenu('technicals.addresses.index') . ' ' . OpenMenu('technicals.introduced') }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-briefcase"></i>
                    <div>فنی حرفه ای</div>
                </a>
                <ul class="menu-sub remove-padding">
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_TECHNICAL))
                        <li class="menu-item {{ ActiveMenu('technicals.index') }}">
                            <a href="{{ route('technicals.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-user-clock me-2"></i>
                                <div>در حال انجام</div>
                            </a>
                        </li>
                        <li class="menu-item {{ ActiveMenu('technicals.introduced') }}">
                            <a href="{{ route('technicals.introduced') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-user-graduate me-2"></i>
                                <div>معرفی شده</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_TECHNICAL_ADDRESS))
                        <li class="menu-item {{ ActiveMenu('technicals.addresses.index') }}">
                            <a href="{{ route('technicals.addresses.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-location-dot me-2"></i>
                                <div>آدرس فنی حرفه ای</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_PERMISSION) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ROLE) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_BRANCH) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_PHONE) or
                Auth::user()->hasPermissionTo(PermissionTitle::INDEX_FAMILIARITY_WAY))
            <li
                class="menu-item {{ OpenMenu('admin.permissions.index') .
                    ' ' .
                    OpenMenu('admin.roles.index') .
                    ' ' .
                    OpenMenu('admin.branches.index') .
                    ' ' .
                    OpenMenu('admin.phones.index') .
                    ' ' .
                    OpenMenu('familiarity-ways.index') }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle"><i class="menu-icon tf-icons bx bx-cog"></i>
                    <div>تنظیمات</div>
                </a>
                <ul class="menu-sub remove-padding">
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_PERMISSION))
                        <li class="menu-item {{ ActiveMenu('admin.permissions.index') }}">
                            <a href="{{ route('admin.permissions.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-lock-alt me-2"></i>
                                <div>{{ __('sidebar.permissions') }}</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_ROLE))
                        <li class="menu-item {{ ActiveMenu('admin.roles.index') }}">
                            <a href="{{ route('admin.roles.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-user-shield me-2"></i>
                                <div>نقش ها</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_BRANCH))
                        <li class="menu-item {{ ActiveMenu('admin.branches.index') }}">
                            <a href="{{ route('admin.branches.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-building me-2"></i>
                                <div>{{ __('sidebar.branches') }}</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_PHONE))
                        <li class="menu-item {{ ActiveMenu('admin.phones.index') }}">
                            <a href="{{ route('admin.phones.index') }}" class="menu-link ">
                                <i class="menu-icon tf-icons bx bx-phone m me-2"></i>
                                <div>{{ __('sidebar.phones') }}</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_FAMILIARITY_WAY))
                        <li class="menu-item {{ ActiveMenu('familiarity-ways.index') }}">
                            <a href="{{ route('familiarity-ways.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-people-robbery me-2"></i>
                                <div>{{ __('sidebar.familiarity-ways') }}</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_SECRETARY) or Auth::user()->hasPermissionTo(PermissionTitle::INDEX_TEACHER) or Auth::user()->hasPermissionTo(PermissionTitle::INDEX_CLERK))
            <li class="menu-item  {{ OpenMenu('teachers.index') . ' ' . OpenMenu('secretaries.index') . ' ' . OpenMenu('clerks.index') }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons fa fa-users"></i>
                    <div>کاربران</div>
                </a>
                <ul class="menu-sub remove-padding">
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_TEACHER))
                        <li class="menu-item {{ ActiveMenu('teachers.index') }}">
                            <a href="{{ route('teachers.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bxs-user-voice me-2"></i>
                                <div>{{ __('sidebar.teachers') }}</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_SECRETARY))
                        <li class="menu-item {{ ActiveMenu('secretaries.index') }}">
                            <a href="{{ route('secretaries.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-users-between-lines me-2"></i>
                                <div>{{ __('sidebar.secretaries') }}</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_CLERK))
                        <li class="menu-item {{ ActiveMenu('clerks.index') }}">
                            <a href="{{ route('clerks.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bxs-user-badge me-2"></i>
                                <div>{{ __('clerks.page_title') }}</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_GOODS))
            <li class="menu-item  {{ OpenMenu('goods.index') . ' ' . OpenMenu('goods.create') }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons fa fa-warehouse"></i>
                    <div>اموال</div>
                </a>
                <ul class="menu-sub remove-padding">
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_GOODS))
                        <li class="menu-item {{ ActiveMenu('goods.index') }}">
                            <a href="{{ route('goods.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-file-lines me-2 text-center"></i>
                                <div>لیست اموال</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::CREATE_GOODS))
                        <li class="menu-item {{ ActiveMenu('goods.create') }}">
                            <a href="{{ route('goods.create') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-file-pen me-2"></i>
                                <div>ثبت اموال</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        {{-- @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_SALES_TEAM))
        <li class="menu-item  {{ OpenMenu('sales-team.index') . ' ' . OpenMenu('sales-team.create') }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fa fa-users"></i>
                <div> تیم فروش</div>
            </a>
            <ul class="menu-sub remove-padding">
                <li class="menu-item {{ ActiveMenu('sales-team.index') }}">
                    <a href="{{ route('sales-team.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons fa-solid fa-list-1-2 me-2 text-center"></i>
                        <div>لیست تیم</div>
                    </a>
                </li>
                @if (Auth::user()->hasPermissionTo(PermissionTitle::CREATE_SALES_TEAM))
                <li class="menu-item {{ ActiveMenu('sales-team.create') }}">
                    <a href="{{ route('sales-team.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons fa-solid fa-file-lines me-2 text-center"></i>
                        <div>ایجاد تیم</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif --}}

        {{-- @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_SURVEY))
        <li class="menu-item  {{ OpenMenu('survey.index') }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fa fa-chart-bar"></i>
                <div>نظرسنجی</div>
            </a>
            <ul class="menu-sub remove-padding">
                <li class="menu-item {{ ActiveMenu('survey.index') }}">
                    <a href="{{ route('survey.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons fa-solid fa-list-alt me-2 text-center"></i>
                        <div>لیست نظر ها</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif --}}
        {{-- @if (Auth::user()->hasPermissionTo(PermissionTitle::INDEX_EXAM))
        <li class="menu-item  {{ OpenMenu('exams.index') }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fa fa-list-1-2"></i>
                <div>آزمون کتبی</div>
            </a>
            <ul class="menu-sub remove-padding">
                <li class="menu-item {{ ActiveMenu('exams.index') }}">
                    <a href="{{ route('exams.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons fa-solid fa-layer-group me-2 text-center"></i>
                        <div>لیست آزمون ها</div>
                    </a>
                </li>
                <li class="menu-item {{ ActiveMenu('exams.create') }}">
                    <a href="{{ route('exams.create') }}" class="menu-link">
                        <i class="menu-icon tf-icons fa-solid fa-marker me-2 text-center"></i>
                        <div>ایجاد آزمون</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif --}}

        @if (Auth::user()->hasPermissionTo(PermissionTitle::REPORTS))
            <li class="menu-header small text-uppercase"><span class="menu-header-text">گزارش مدیریتی</span></li>
            <li
                class="menu-item  {{ OpenMenu('reports.payment-change-log') .
                    ' ' .
                    OpenMenu('reports.verification-code') .
                    ' ' .
                    OpenMenu('reports.course-register-change-log') .
                    ' ' .
                    OpenMenu('reports.order-item-change-log') .
                    ' ' .
                    OpenMenu('reports.secretary-sales') .
                    ' ' .
                    OpenMenu('reports.secretary-follows') .
                    ' ' .
                    OpenMenu('reports.financial') .
                    ' ' .
                    OpenMenu('reports.send-sms-log') }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons fa fa-chart-line"></i>
                    <div>گزارش های مدیریتی</div>
                </a>
                <ul class="menu-sub remove-padding">
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REPORTS_PAYMENT_CHANGE_LOG))
                        <li class="menu-item {{ ActiveMenu('reports.payment-change-log') }}">
                            <a href="{{ route('reports.payment-change-log') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-money-bill me-2 text-center"></i>
                                <div>تغییرات پرداخت</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REPORTS_VERIFICATION_CODE))
                        <li class="menu-item {{ ActiveMenu('reports.verification-code') }}">
                            <a href="{{ route('reports.verification-code') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-message me-2"></i>
                                <div>کدهای ورود</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REPORTS_COURSE_REGISTER_CHANGE_LOG))
                        <li class="menu-item {{ ActiveMenu('reports.course-register-change-log') }}">
                            <a href="{{ route('reports.course-register-change-log') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-book-medical me-2 text-center"></i>
                                <div>تغییرات ثبت نام دوره</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REPORTS_ORDER_ITEM_CHANGE_LOG))
                        <li class="menu-item {{ ActiveMenu('reports.order-item-change-log') }}">
                            <a href="{{ route('reports.order-item-change-log') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-box-open me-2 text-center"></i>
                                <span>تغییرات سفارش</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REPORTS_SECRETARY_SALES))
                        <li class="menu-item {{ ActiveMenu('reports.secretary-sales') }}">
                            <a href="{{ route('reports.secretary-sales') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-bag-shopping me-2 text-center"></i>
                                <span>فروش مشاورین</span>
                            </a>
                        </li>
                        <li class="menu-item {{ ActiveMenu('reports.secretary-follows') }}">
                            <a href="{{ route('reports.secretary-follows') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa fa-add me-2 text-center"></i>
                                <span> پیگیری مشاورین</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REPORTS_FINANCIAL))
                        <li class="menu-item {{ ActiveMenu('reports.financial') }}">
                            <a href="{{ route('reports.financial') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-money-bill me-2 text-center"></i>
                                <span>گزارش مالی</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermissionTo(PermissionTitle::REPORTS_SEND_SMS_LOG))
                        <li class="menu-item {{ ActiveMenu('reports.send-sms-log') }}">
                            <a href="{{ route('reports.send-sms-log') }}" class="menu-link">
                                <i class="menu-icon tf-icons fa-solid fa-message me-2 text-center"></i>
                                <div>پیامک های ارسال شده</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <li class="menu-item ">
            <a href="{{ route('auth.logout') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-power-off'></i>
                <span class="align-middle">{{ __('public.logout') }}</span>
            </a>
        </li>
        <li>&nbsp;</li>
        <li>&nbsp;</li>
        <li>&nbsp;</li>
    </ul>
</aside>
