<!DOCTYPE html>
<html lang="fa" class="light-style layout-navbar-fixed layout-menu-fixed" dir="rtl" data-theme="theme-semi-dark" data-assets-path="{{ asset('admin-panel/assets/') }}"
    data-template="vertical-menu-template">

<head>
    <link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/css/pages/page-auth.css') }}">
    @include('users.layouts.head')
    @livewireStyles
</head>

<body>
    @yield('content')
    @include('users.layouts.scripts')
    @livewireScripts
    <script src="{{ asset('admin-panel/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('admin-panel/assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('admin-panel/assets/js/pages-auth-two-steps.js') }}"></script>
    <x-livewire-alert::scripts />
</body>

</html>
