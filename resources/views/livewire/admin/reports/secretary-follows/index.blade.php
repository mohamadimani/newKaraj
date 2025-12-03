<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">گزارش پیگیری های مشاورین</span>
        </div>
        <style>
            .progressbar {
                position: relative;
            }

            .progressbar-text {
                color: rgb(146, 44, 136);
                position: absolute;
                left: 60%;
                top: 45%;
            }
        </style>
        <div class="row ">
            <div class="card-body ">
                <div class="row">
                    {{-- search and chart  --}}
                    <div class="col-md-12 mb-4">
                        <div class="card ">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-md-2 p-1">
                                        <label class="font-13 mb-1" for="startDate">از تاریخ</label>:
                                        <input data-jdp type="text" wire:model="startDate" id="startDate" class="form-control " placeholder="از تاریخ">
                                    </div>
                                    <div class="col-md-2 p-1">
                                        <label class="font-13 mb-1" for="endDate">تا تاریخ</label>:
                                        <input data-jdp type="text" wire:model="endDate" id="endDate" class="form-control" placeholder="تا تاریخ">
                                        @include('admin.layouts.jdp')
                                    </div>
                                    <div class="col-md-4 p-1" wire:ignore>
                                        <label class="font-13 mb-1" for="secretaryId">مشاور</label>:
                                        <select class="form-control select2" wire:model="secretaryId" id="secretaryId" onchange="setSecretaryId()">
                                            <option value="0"> -- انتخاب -- </option>
                                            @foreach ($secretaries as $secretaryRow)
                                                <option value="{{ $secretaryRow->id }}">{{ $secretaryRow->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 p-1">
                                        <button class="btn btn-outline-primary mt-4" wire:click='getSecretarySaleInfo()' wire:loading.remove>نمایش</button>
                                        <i class="bx bx-loader-circle bx-spin text-info mt-4 " wire:loading></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- all data report  --}}
                    <div class="col-md-4">
                        <div class="card dashboard-progress height490">
                            <div class="position-absolute card-top-buttons">
                                <button class="btn btn-header-light icon-button">
                                    <i class="simple-icon-refresh"></i>
                                </button>
                            </div>
                            <div class="card-body p-3">
                                <h5 class="card-title font-14">مقایسه کلی</h5>
                                @php
                                    $reports = [
                                        'clueWithoutFollow' => [
                                            'title' => 'سرنخ های بدون پیگیری از کل',
                                            'persent' => $clueWithoutFollow > 0 ? round(($clueWithoutFollow * 100) / $clues, 1) : 0,
                                            'counter' => number_format($clueWithoutFollow) . '/' . number_format($clues),
                                        ],
                                        'clueHasFollow' => [
                                            'title' => 'سرنخ های پیگیری شده',
                                            'persent' => $clueHasFollow > 0 ? round(($clueHasFollow * 100) / $clues, 1) : 0,
                                            'counter' => number_format($clueHasFollow) . '/' . number_format($clues),
                                        ],
                                        'stepOneFollow' => [
                                            'title' => 'پیگیری های مرحله اول',
                                            'persent' => $clueHasFollow > 0 ? round(($stepOneFollow * 100) / $clueHasFollow, 1) : 0,
                                            'counter' => number_format($stepOneFollow) . '/' . number_format($clueHasFollow),
                                        ],
                                        'stepTwoFollow' => [
                                            'title' => 'پیگیری های مرحله دوم',
                                            'persent' => $clueHasFollow > 0 ? round(($stepTwoFollow * 100) / $clueHasFollow, 1) : 0,
                                            'counter' => number_format($stepTwoFollow) . '/' . number_format($clueHasFollow),
                                        ],
                                        'stepThreeFollow' => [
                                            'title' => 'پیگیری های مرحله سوم',
                                            'persent' => $clueHasFollow > 0 ? round(($stepThreeFollow * 100) / $clueHasFollow, 1) : 0,
                                            'counter' => number_format($stepThreeFollow) . '/' . number_format($clueHasFollow),
                                        ],
                                        'notAnswerFollow' => [
                                            'title' => 'پیگیری های عدم پاسخ',
                                            'persent' => $clueHasFollow > 0 ? round(($notAnswerFollow * 100) / $clueHasFollow, 1) : 0,
                                            'counter' => number_format($notAnswerFollow) . '/' . number_format($clueHasFollow),
                                        ],
                                        'closedFollow' => [
                                            'title' => 'پیگیری های بسته شده',
                                            'persent' => $clueHasFollow > 0 ? round(($closedFollow * 100) / $clueHasFollow, 1) : 0,
                                            'counter' => number_format($closedFollow) . '/' . number_format($clueHasFollow),
                                        ],
                                    ];
                                @endphp
                                @foreach ($reports as $report)
                                    <div class="mb-3">
                                        <div class="row" wire:replace>
                                            <div class="col-8">
                                                <p class="mb-2 font-13"><span>{{ $report['title'] }}</span>
                                                    <span class="float-right  ">{{ $report['counter'] }}</span>
                                                </p>
                                                <div class="progress" style="height: 9px">
                                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?= $report['persent'] ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-4 m-auto">
                                                <div role="progressbar" class="progress-bar-circle position-relative m-auto" id="progressMCountsChangeAll2" data-color="#922c88"
                                                    data-trailcolor="#d7d7d7" aria-valuemax="100" aria-valuenow="0" data-show-percent="true">
                                                    <svg viewBox="0 0 100 100" style="display: block; width: 60%;" class="d-flex">
                                                        <path d="M 50,50 m 0,-48 a 48,48 0 1 1 0,96 a 48,48 0 1 1 0,-96" stroke="#d7d7d7" stroke-width="4" fill-opacity="0"></path>
                                                        <path d="M 50,50 m 0,-48 a 48,48 0 1 1 0,96 a 48,48 0 1 1 0,-96" stroke="#922c88" stroke-width="4" fill-opacity="0"
                                                            style="stroke-dasharray: 300; stroke-dashoffset: <?= 300 - $report['persent'] * 3 ?>;"></path>
                                                    </svg>
                                                    <div class="progressbar-text p-0 m-0 font-12"> {{ $report['persent'] }}% </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- End all data report  --}}

                    {{-- this secretary data report  --}}
                    <div class="col-md-4">
                        <div class="card dashboard-progress height490">
                            <div class="position-absolute card-top-buttons">
                                <button class="btn btn-header-light icon-button">
                                    <i class="simple-icon-refresh"></i>
                                </button>
                            </div>
                            <div class="card-body p-3">
                                <h5 class="card-title font-14">گزارش مشاور : <span> {{ $secretary?->user?->full_name }}</span></h5>
                                @php
                                    $reports = [
                                        'clueWithoutFollow' => [
                                            'title' => 'سرنخ های بدون پیگیری از کل',
                                            'persent' => $clueWithoutFollow > 0 ? round(($clueWithoutFollow * 100) / $clues, 1) : 0,
                                            'counter' => number_format($clueWithoutFollow) . '/' . number_format($clues),
                                        ],
                                        'clueHasFollow' => [
                                            'title' => 'سرنخ های پیگیری شده',
                                            'persent' => $clueHasFollow > 0 ? round(($secretaryClueHasFollow * 100) / $clueHasFollow, 1) : 0,
                                            'counter' => number_format($secretaryClueHasFollow) . '/' . number_format($clueHasFollow),
                                        ],
                                        'stepOneFollow' => [
                                            'title' => 'پیگیری های مرحله اول',
                                            'persent' => $secretaryClueHasFollow > 0 ? round(($secretaryStepOneFollow * 100) / $secretaryClueHasFollow, 1) : 0,
                                            'counter' => number_format($secretaryStepOneFollow) . '/' . number_format($secretaryClueHasFollow),
                                        ],
                                        'stepTwoFollow' => [
                                            'title' => 'پیگیری های مرحله دوم',
                                            'persent' => $secretaryClueHasFollow > 0 ? round(($secretaryStepTwoFollow * 100) / $secretaryClueHasFollow, 1) : 0,
                                            'counter' => number_format($secretaryStepTwoFollow) . '/' . number_format($secretaryClueHasFollow),
                                        ],
                                        'stepThreeFollow' => [
                                            'title' => 'پیگیری های مرحله سوم',
                                            'persent' => $secretaryClueHasFollow > 0 ? round(($secretaryStepThreeFollow * 100) / $secretaryClueHasFollow, 1) : 0,
                                            'counter' => number_format($secretaryStepThreeFollow) . '/' . number_format($secretaryClueHasFollow),
                                        ],
                                        'notAnswerFollow' => [
                                            'title' => 'پیگیری های عدم پاسخ',
                                            'persent' => $secretaryClueHasFollow > 0 ? round(($secretaryNotAnswerFollow * 100) / $secretaryClueHasFollow, 1) : 0,
                                            'counter' => number_format($secretaryNotAnswerFollow) . '/' . number_format($secretaryClueHasFollow),
                                        ],
                                        'closedFollow' => [
                                            'title' => 'پیگیری های بسته شده',
                                            'persent' => $secretaryClueHasFollow > 0 ? round(($secretaryClosedFollow * 100) / $secretaryClueHasFollow, 1) : 0,
                                            'counter' => number_format($secretaryClosedFollow) . '/' . number_format($secretaryClueHasFollow),
                                        ],
                                    ];
                                @endphp
                                @foreach ($reports as $report)
                                    <div class="mb-3">
                                        <div class="row" wire:replace>
                                            <div class="col-8">
                                                <p class="mb-2 font-13"><span>{{ $report['title'] }}</span>
                                                    <span class="float-right  ">{{ $report['counter'] }}</span>
                                                </p>
                                                <div class="progress" style="height: 9px">
                                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?= $report['persent'] ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-4 m-auto">
                                                <div role="progressbar" class="progress-bar-circle position-relative m-auto" id="progressMCountsChangeAll2" data-color="#922c88"
                                                    data-trailcolor="#d7d7d7" aria-valuemax="100" aria-valuenow="0" data-show-percent="true">
                                                    <svg viewBox="0 0 100 100" style="display: block; width: 60%;" class="d-flex">
                                                        <path d="M 50,50 m 0,-48 a 48,48 0 1 1 0,96 a 48,48 0 1 1 0,-96" stroke="#d7d7d7" stroke-width="4" fill-opacity="0"></path>
                                                        <path d="M 50,50 m 0,-48 a 48,48 0 1 1 0,96 a 48,48 0 1 1 0,-96" stroke="#922c88" stroke-width="4" fill-opacity="0"
                                                            style="stroke-dasharray: 300; stroke-dashoffset: <?= 300 - $report['persent'] * 3 ?>;"></path>
                                                    </svg>
                                                    <div class="progressbar-text p-0 m-0 font-12"> {{ $report['persent'] }}% </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- End all data report  --}}
                </div>
            </div>
        </div>
    </div>
    <script>
        function setSecretaryId() {
            const value = $('select#secretaryId').val();
            @this.setSecretaryId(value)
        }
    </script>
</div>
