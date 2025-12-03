<!DOCTYPE html>
<html lang="fa" class="light-style layout-navbar-fixed layout-menu-fixed" dir="rtl" data-theme="theme-semi-dark" data-assets-path="{{ asset('admin-panel/assets/') }}"
    data-template="vertical-menu-template">

<head>
    @include('admin.layouts.head')
    @livewireStyles
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('admin.layouts.sidebar')
            <div class="layout-page">
                @include('admin.layouts.navbar')
                <div class="content-wrapper">

                    @yield('content')

                    @include('admin.layouts.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    @include('admin.layouts.scripts')
    @livewireScripts
    <script src="{{ asset('admin-panel/assets/sweet_alert/sweetalert2.all.min.js') }}"></script>
    <x-livewire-alert::scripts />
</body>

</html>
