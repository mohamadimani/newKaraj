<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>{{ ENV('APP_NAME') }}</title>
<link rel="icon" type="image/webp" href="/portal_logo.webp">
<meta name="description" content="{{ ENV('APP_NAME') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/fonts/boxicons.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/fonts/fontawesome.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/fonts/flag-icons.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/css/rtl/core.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/css/rtl/theme-semi-dark.css') }}">
{{--
<link rel="stylesheet" href="{{ asset('admin-panel/assets/css/demo.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/css/rtl/rtl.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/typeahead-js/typeahead.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/apex-charts/apex-charts.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/select2/select2.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/quill/editor-fa.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/flatpickr/flatpickr.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/plyr/plyr.css') }}">
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/tagify/tagify.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('admin-panel/plugins/ckeditor/contents.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('admin-panel/assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}">
{{-- editor scripts must be here --}}
<script src="{{ asset('admin-panel/assets/vendor/libs/quill/katex.js') }}"></script>
<script src="{{ asset('admin-panel/assets/vendor/libs/quill/quill.js') }}"></script>


<script src="{{ asset('admin-panel/assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('admin-panel/assets/vendor/js/template-customizer.js') }}"></script>
<script src="{{ asset('admin-panel/assets/js/config.js') }}"></script>

<script src="{{ asset('admin-panel/assets/js/charts-chartjs.js') }}"></script>
<style>
    .table-responsive th {
        padding: 4px !important;
        text-align: center !important;
        font-size: 13px !important;
    }

    .table-responsive td {
        padding: 4px !important;
        font-size: 13px !important;
    }

    .dropdown-item {
        line-height: 10px !important;
    }
</style>