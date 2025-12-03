@extends('users.layouts.master')

@section('content')
<style>
    table td,
    table th {
        padding: 6px 4px !important;
    }

    .card-body {
        padding: 10px !important;
    }
</style>

<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 text-gray-800">هدایای من</h1>
            </div>
            <div class="col-12 mb-4 d-none" id="video">
                <label class="btn btn-danger btn-sm" onclick="closeVideo()"> X </label>
                <video class="w-100" poster="{{ asset('admin-panel/assets/img/video-placeholder.jpg') }}" id="plyr-video-player" playsinline controls>
                    <source src="" type="video/mp4">
                </video>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th>نام هدیه</th>
                            <th>نمایش</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $links = [
                                'https://dl.newdeniz.com/kasbokar/lesson1-2.mp4',
                                'https://dl.newdeniz.com/kasbokar/lesson2.mp4',
                                'https://dl.newdeniz.com/kasbokar/lesson3.mp4',
                                'https://dl.newdeniz.com/kasbokar/lesson4-1.mp4',
                                'https://dl.newdeniz.com/kasbokar/lesson5.mp4',
                                'https://dl.newdeniz.com/kasbokar/lesson6.mp4',
                            ];
                        @endphp
                        @foreach ($links as $key => $item)
                            <tr>
                                <td class="text-center ">
                                    <span>دوره مشاوره راه اندازی کسب و کار و قرارداد نویسی</span>
                                    <span>جلسه {{ $key + 1 }}</span>
                                </td>
                                <td class="text-center">
                                    <button onclick="showVideo(this)" data-url="{{ $item }}" class="btn btn-success btn-sm "> <i class="fa fa-play "></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function showVideo(item) {
        var url = $(item).data('url');
        document.getElementById('video').classList.remove('d-none');
        document.getElementById('plyr-video-player').src = url;
    }

    function closeVideo() {
        document.getElementById('video').classList.add('d-none');
        document.getElementById('plyr-video-player').src = '';
    }
</script>
@endsection

