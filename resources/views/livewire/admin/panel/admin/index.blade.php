<div class="container-fluid flex-grow-1 container-p-y">
    <div class="col-sm-12">
        <div class="row">
            @foreach ($salesTeams as $salesTeam)
                @php
                    $eachGroupSales = 0;
                @endphp
                <div class="col-md-3 col-sm-12 mb-2">
                    <div class="card h-100">
                        <div class="card-body pb-2">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="card-info">
                                        <h5 class="card-title mb-0 me-2 primary-font font-13">{{ $salesTeam->title }}</h5>
                                        <div class="mt-2">
                                            @foreach ($salesTeam->secretaries as $salesTeamSecretary)
                                                @php
                                                    $eachGroupSales += $salesTeamSecretary->secretary->lastMonthSales();
                                                    $targetPercentage = ($eachGroupSales * 100) / $salesTeam->monthly_sale_target;
                                                    $isManager = $salesTeamSecretary->secretary_id == $salesTeam->sales_team_manager_id;
                                                @endphp
                                                <div class="d-flex align-items-center mb-1">
                                                    <div class="me-2">
                                                        <div class="fw-semibold font-13">{{ $salesTeamSecretary->secretary->user->full_name }}</div>
                                                        <small class="text-info font-12">فروش ماهانه: {{ $salesTeamSecretary->secretary->lastMonthSales() }}</small>
                                                    </div>
                                                    <div class="me-2 d-block">
                                                        @if ($isManager)
                                                            <span class="badge bg-primary p-1 mb-3">سرتیم</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light p-3">
                            <div class="mb-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark font-12">پیشرفت</span>
                                    <span class="badge bg-info">{{ number_format($targetPercentage, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;background-color: white">
                                    <div class="progress-bar bg-primary font-12" role="progressbar" style="width: {{ min($targetPercentage, 100) }}%" aria-valuenow="{{ $targetPercentage }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-dark font-12">فروش فعلی</small>
                                    <div class="fw-semibold font-12">{{ number_format($eachGroupSales) }}</div>
                                </div>
                                <div class="text-end">
                                    <small class="text-dark font-12">هدف</small>
                                    <div class="fw-semibold font-12">{{ number_format($salesTeam->monthly_sale_target) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
