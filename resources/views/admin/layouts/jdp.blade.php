<link rel="stylesheet" href="{{ asset('admin-panel/jdp/jalalidatepicker.min.css') }}" />
<script src="{{ asset('admin-panel/jdp/jalalidatepicker.min.js') }}"></script>
<style>
    .jdp-days .jdp-day-name {
        font-weight: 500 !important;
        z-index: 1500 !important;
    }
    jdp-container {
        z-index: 1500 !important;
    }
</style>
@if (isset($time) and $time == true)
    <script>
        jalaliDatepicker.startWatch({
            time: true,
            zIndex: 1500,
            showEmptyBtn: true
        });
    </script>
@else
    <script>
        jalaliDatepicker.startWatch({
            time: false,
            zIndex: 1500,
            showEmptyBtn: true
        });
    </script>
@endif
