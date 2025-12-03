<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">گزارش فروش دوره های حضوری مشاورین</span>
        </div>
        <div class="row ">
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
            <div class="col-12 ">
                <div class="card-body p-1">
                    <div class="row">
                        {{-- search and chart  --}}
                        <div class="col-md-8 mb-4">
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
                                            <select class="form-control select2" wire:model="secretaryId" id="secretaryId" onchange="myFunction()">
                                                <option value="0"> -- انتخاب -- </option>
                                                @foreach ($secretaries as $secretary)
                                                    <option value="{{ $secretary->id }}">{{ $secretary->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 p-1">
                                            <button class="btn btn-outline-primary mt-4" wire:click='getSecretarySaleInfo()' wire:loading.remove>نمایش</button>
                                            <button class="btn btn-outline-primary mt-4" onclick="showChart()" wire:loading.remove>چارت</button>
                                            <i class="bx bx-loader-circle bx-spin text-info mt-4 " wire:loading></i>
                                        </div>
                                    </div>
                                </div>
                                <div id="secretaryClueStudentChart">
                                    <div class="chart card-body pt-0">
                                        <div id="cluesAndStudentsChartId">
                                            <div class="chart card-body pt-0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- all data report  --}}
                        <div class="col-md-4 mb-4">
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
                                            'allClueToStudent' => [
                                                'title' => 'تبدیل سرنخ به کارآموز کل مشاور ها',
                                                'persent' => $cluesCount > 0 ? round(($studentCount * 100) / $cluesCount, 1) : 0,
                                                'counter' => $studentCount . '/' . $cluesCount,
                                            ],
                                            'secretaryClue' => [
                                                'title' => 'سرنخ های این مشاور از کل',
                                                'persent' => $cluesCount > 0 ? round(($secretaryClues * 100) / $cluesCount, 1) : 0,
                                                'counter' => $secretaryClues . '/' . $cluesCount,
                                            ],
                                            'secretaryClueStudent' => [
                                                'title' => 'تبدیل سرنخ به کارآموز این مشاور',
                                                'persent' => $secretaryClues > 0 ? round(($secretaryCluesToStudent * 100) / $secretaryClues, 2) : 0,
                                                'counter' => $secretaryCluesToStudent . '/' . $secretaryClues,
                                            ],
                                            'secretaryStudents' => [
                                                'title' => ' کارآموز های این مشاور از کل',
                                                'persent' => $studentCount > 0 ? round(($secretaryStudents * 100) / $studentCount, 1) : 0,
                                                'counter' => $secretaryStudents . '/' . $studentCount,
                                            ],
                                            'secretaryCancel' => [
                                                'title' => 'انصرافی های این مشاور از کل',
                                                'persent' => $cancelCount > 0 ? round(($secretaryCancels * 100) / $cancelCount, 1) : 0,
                                                'counter' => $secretaryCancels . '/' . $cancelCount,
                                            ],
                                            'secretaryReserve' => [
                                                'title' => 'رزرو های این مشاور از کل',
                                                'persent' => $reservedCount > 0 ? round(($secretaryReserved * 100) / $reservedCount, 1) : 0,
                                                'counter' => $secretaryReserved . '/' . $reservedCount,
                                            ],
                                        ];
                                    @endphp
                                    @foreach ($reports as $report)
                                        <div class="mb-3">
                                            <div class="row" wire:replace>
                                                <div class=" col-8">
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
                    </div>
                </div>
                {{-- per professsions  --}}
                <div class="row">
                    @if (count($professionStudents) > 0)
                        @foreach ($professionStudents as $professionId => $professionItems)
                            @php
                                $professionItemCount = $professionItems->count();
                                $secretaryStudentInProfessionCount = isset($secretaryStudentInProfession[$professionId]) ? $secretaryStudentInProfession[$professionId]->count() : 0;

                                $persent = $professionItemCount > 0 ? round(($secretaryStudentInProfessionCount * 100) / $professionItemCount, 1) : 0;
                            @endphp
                            <div class="mb-4 col-4">
                                <div class="card py-2">
                                    <div class="row m-2" wire:replace>
                                        <div class=" col-8 ">
                                            <p class="mb-2">
                                                <span class="text-primary d-inline-block">{{ $professionList[$professionId] }}</span>
                                                <span class="float-right  " id="MCounts0">{{ $secretaryStudentInProfessionCount }}/{{ $professionItemCount }} </span>
                                            </p>
                                            <div class="progress">
                                                <div class="progress-bar" style="width:<?php echo $persent; ?>%;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col-4 ">
                                            <div role="progressbar" class="progress-bar-circle position-relative m-auto" id="progressMCounts20" data-color="#922c88" data-trailcolor="#d7d7d7"
                                                aria-valuemax="100" aria-valuenow="0" data-show-percent="true" style="width: 0%;"><svg viewBox="0 0 100 100" style="display: block; width: 100%;">
                                                    <path d="M 50,50 m 0,-48 a 48,48 0 1 1 0,96 a 48,48 0 1 1 0,-96" stroke="#d7d7d7" stroke-width="4" fill-opacity="0"></path>
                                                    <path d="M 50,50 m 0,-48 a 48,48 0 1 1 0,96 a 48,48 0 1 1 0,-96" stroke="#922c88" stroke-width="4" fill-opacity="0"
                                                        style="stroke-dasharray: 301.635, 301.635; stroke-dashoffset: 301.635;"></path>
                                                </svg>
                                                <div class="progressbar-text"
                                                    style="position: absolute; left: 50%; top: 50%; padding: 0px; margin: 0px; transform: translate(-50%, -50%); color: rgb(146, 44, 136);">
                                                    {{ $persent }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        function showChart() {
            var startDate = $('input#startDate').val();
            var endDate = $('input#endDate').val();
            var secretaryId = $('select#secretaryId').val();
            var url = '{{ route('reports.secretary-sales-data') }}'
            var myChart;
            if (!endDate || !startDate) {
                $('#cluesAndStudentsChartId').html('<h5 class="alert text-danger text-center border-primary ">  تاریخ را انتخاب کنبد</h5>').fadeIn('slow').fadeOut(3500);
                return 0;
            }
            if (secretaryId == 0) {
                $('#cluesAndStudentsChartId').html('<h5 class="alert text-danger text-center border-primary ">لطفا مشاور را انتخاب کنبد</h5>').fadeIn('slow').fadeOut(3500);
                return 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    secretaryId: secretaryId,
                    startDate: startDate,
                    endDate: endDate,
                },
                success: function(data) {
                    let chartStaffEnzo = $('#cluesAndStudentsChartId');
                    chartStaffEnzo.html('');

                    let createChartStaff = () => {
                        return '<div class="chart card-body pt-0"><canvas id="cluesAndStudentsChart" style="width:100%;"></canvas></div>'
                    }
                    chartStaffEnzo.append(
                        createChartStaff({})
                    )

                    const ctx = document.getElementById('cluesAndStudentsChart').getContext('2d');
                    if (myChart) {
                        myChart.destroy();
                    }
                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.label,
                            datasets: [{
                                    fill: false,
                                    lineTension: 0,
                                    backgroundColor: '#00ffee',
                                    borderColor: '#00ffee',
                                    data: data.clues,
                                    label: 'سرنخ'
                                },
                                {
                                    fill: false,
                                    lineTension: 0,
                                    backgroundColor: '#00aaaa',
                                    borderColor: '#00aaaa',
                                    data: data.students,
                                    label: 'کارآموز'
                                }
                            ]
                        },
                        options: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        min: 0,
                                        max: 10
                                    }
                                }],
                            }
                        }
                    });
                }
            });
        }

        function myFunction() {
            const value = $('select#secretaryId').val();
            @this.setSecretaryId(value)
        }
        myFunction()
    </script>
</div>
