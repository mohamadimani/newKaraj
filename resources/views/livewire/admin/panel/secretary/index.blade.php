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

    <div class="row">
        <div class="col-md-10 col-sm-12">
            <div class="card pb-3">
                <div class="align-items-center card-header d-flex justify-content-between">
                    <span class="font-20 fw-bold heading-color"><span class="text-info">{{ $filtersName[$filter] }}</span> : ({{ count($data) }}) عدد </span>
                </div>
                @include('admin.layouts.alerts')
                <div class="d-flex gap-2 col-md-12 p-2 mb-2">
                    <div class="col-md-4">
                        <label for="search">{{ __('public.search') }}</label> :
                        <input type="text" class="form-control " wire:model.live.debounce.500ms="search" placeholder="جستجو ...">
                    </div>
                    <div class="col-md-2">
                        <label for="startDate">از</label> :
                        <i class="bx bx-loader-circle bx-spin text-info" wire:loading></i>
                        <i class="fa fa-eraser  text-info cursor-pointer float-end" wire:click="$set('startDate', null)"></i>
                        <input data-jdp type="text" class="form-control " wire:model.live="startDate" placeholder="تاریخ">
                        @include('admin.layouts.jdp', ['time' => false])
                    </div>
                    <div class="col-md-2">
                        <label for="endDate">تا</label> :
                        <i class="bx bx-loader-circle bx-spin text-info" wire:loading></i>
                        <i class="fa fa-eraser  text-info cursor-pointer float-end" wire:click="$set('endDate', null)"></i>
                        <input data-jdp type="text" class="form-control " wire:model.live="endDate" placeholder="تاریخ">
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered table-striped table-hover">
                        <style>
                            .follow-description {
                                white-space: pre-wrap !important;
                            }

                            .table-responsive td {
                                padding: 3px !important;
                            }

                            .active-filter-btn {
                                border: 1px solid blue !important;
                                box-shadow: 0px 0px 7px blue;
                            }
                        </style>
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>{{ __('users.full_name') }}</th>
                                <th>{{ __('users.mobile') }}</th>
                                @if ($filter !== 'todayFollow')
                                    <th>پیگیری ها</th>
                                @endif
                                <th>تاریخ</th>
                                <th>{{ __('clues.favorite_professions') }}</th>
                                <th>{{ __('clues.secretary') }}</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($data as $row => $clue)
                                <tr class="text-center">
                                    <td>{{ $row + 1 }}</td>
                                    <td>{{ $clue->user?->fullName }}</td>
                                    <td>{{ $clue->user?->mobile }}</td>
                                    @if ($filter == 'todayFollow')
                                        <td>
                                            <span class="font-13 follow-description ">{{ georgianToJalali($clue->created_at, true) }}</span>
                                        </td>
                                    @else
                                        <td>
                                            @if (count($clue->user->followUps) > 0)
                                                @foreach ($clue->user->followUps as $index => $followUp)
                                                    @if ($followUp)
                                                        <span class="font-13 follow-description ">{{ $followUp->description }}</span>
                                                    @endif
                                                    @if (count($clue->user->followUps) > $index + 1)
                                                        <hr class="p-0 m-2 text-info">
                                                    @endif
                                                @endforeach
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        <td>
                                            @if (count($clue->user->followUps) > 0)
                                                @foreach ($clue->user->followUps as $index => $followUp)
                                                    @if ($followUp)
                                                        <span class="font-13 follow-description ">{{ georgianToJalali($followUp->created_at, true) }}</span>
                                                    @endif
                                                    @if (count($clue->user->followUps) > $index + 1)
                                                        <hr class="p-0 m-2 text-info">
                                                    @endif
                                                @endforeach
                                            @else
                                                ---
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        @if (count($clue->professions) > 0)
                                            @foreach ($clue->professions as $profession)
                                                @if (!$profession->pivot->course_register_id)
                                                    <span class="badge bg-label-primary">{{ $profession->title }}</span><br>
                                                @endif
                                            @endforeach
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td><span class="badge bg-label-warning">{{ $clue->secretary?->user?->full_name }}</span></td>
                                    <td>
                                        @if ($filter == 'closedFollow')
                                            <span class="badge bg-label-danger m-0 p-0">پیگیری بسته شده</span>
                                        @else
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <span class="dropdown-item cursor-pointer" data-user-id="{{ $clue->user_id }}" data-bs-toggle="modal" data-bs-target="#addFollowUpModal"
                                                    onclick="myFunction({{ $clue->user_id }})">
                                                    <i class="tf-icons bx bx-time-five"></i> {{ __('follow_ups.add_follow_up') }}
                                                </span>
                                                @if ($clue->user?->id !== Auth::user()->id and isAdminNumber())
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="{{ route('auth.login-by-user-id', [$clue->user?->id ?? 1]) }}">
                                                        <i class="bx bx-log-in me-1"></i> ورود با دسترسی کاربر</a>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($data) === 0)
                        <div class="text-center py-5">
                            {{ __('messages.empty_table') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-2 position-fixed top-10 end-0" style="margin-left: -10px;">
            <div class="card">
                <div class="align-items-center card-header d-flex justify-content-between pb-2">
                    <span class="font-14 fw-bold heading-color">پیگیری ها</span>
                </div>
                <div class="p-3">
                    <h5 class="mb-2 font-14 btn btn-warning w-100  {{ $filter == 'todayFollow' ? 'active-filter-btn' : '' }}" wire:click="setFollowUpFilter('todayFollow')">سرنخ های بدون پیگیری</h5>
                    <h5 class="mb-2 font-14 btn btn-success w-100 {{ $filter == 'doneFollow' ? 'active-filter-btn' : '' }}" wire:click="setFollowUpFilter('doneFollow')">پیگیری های انجام شده</h5>
                    <h5 class="mb-2 font-14 btn btn-info w-100 {{ $filter == 'twoStepFollow' ? 'active-filter-btn' : '' }}" wire:click="setFollowUpFilter('twoStepFollow')">پیگیری های مرحله دوم</h5>
                    <h5 class="mb-2 font-14 btn btn-info w-100 {{ $filter == 'threeStepFollow' ? 'active-filter-btn' : '' }}" wire:click="setFollowUpFilter('threeStepFollow')">پیگیری های مرحله سوم</h5>
                    <h5 class="mb-2 font-14 btn btn-danger w-100 {{ $filter == 'notAnswerFollow' ? 'active-filter-btn' : '' }}" wire:click="setFollowUpFilter('notAnswerFollow')">پیگیری های عدم پاسخ</h5>
                    <h5 class="mb-2 font-14 btn btn-gray w-100 {{ $filter == 'closedFollow' ? 'active-filter-btn' : '' }}" wire:click="setFollowUpFilter('closedFollow')">پیگیری های بسته شده</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="modal  " id="addFollowUpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4 mt-0 mt-md-n2">
                        <h5 class="secondary-font">{{ __('follow_ups.add_follow_up') }} :
                            <i class="bx bx-loader-circle bx-spin text-info" wire:loading></i>
                            <span class="text-info" wire:loading.remove>{{ $userName }}</span>
                        </h5>
                    </div>
                    @include('admin.layouts.alerts')
                    <div class="row g-3 pt-3">
                        <div class="col-sm-12 mb-1">
                            <label class="form-label" for="title">{{ __('follow_ups.title') }}</label>
                            <select wire:model="title" id="title" class="form-control text-start" required>
                                <option value="">---</option>
                                <option value="{{ __('follow_ups.titles.register_course') }}">{{ __('follow_ups.titles.register_course') }}</option>
                                <option value="{{ __('follow_ups.titles.receive_payment') }}">{{ __('follow_ups.titles.receive_payment') }}</option>
                                <option value="{{ __('follow_ups.titles.reject_payment') }}">{{ __('follow_ups.titles.reject_payment') }}</option>
                                <option value="{{ __('follow_ups.titles.complaints_follow_up') }}">{{ __('follow_ups.titles.complaints_follow_up') }}</option>
                                <option value="{{ __('follow_ups.titles.technical_follow_up') }}">{{ __('follow_ups.titles.technical_follow_up') }}</option>
                            </select>
                        </div>
                        <div class="col-sm-12 mb-1">
                            <label class="form-label" for="description">{{ __('follow_ups.description') }}</label>
                            <textarea wire:model="description" rows="3" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        @if ($filter == 'todayFollow')
                            <button type="submit" class="btn btn-info me-sm-2 me-1" wire:click="addFollowUp('step1')" wire:loading.attr="disabled"
                                wire:loading.remove>{{ __('public.submit') }}</button>
                        @elseif ($filter == 'doneFollow')
                            <button type="submit" class="btn btn-info me-sm-2 me-1" wire:click="addFollowUp('step2')" wire:loading.attr="disabled"
                                wire:loading.remove>{{ __('public.submit') }}</button>
                        @elseif ($filter == 'twoStepFollow')
                            <button type="submit" class="btn btn-info me-sm-2 me-1" wire:click="addFollowUp('step3')" wire:loading.attr="disabled"
                                wire:loading.remove>{{ __('public.submit') }}</button>
                        @elseif ($filter == 'threeStepFollow')
                            <button type="submit" class="btn btn-info me-sm-2 me-1" wire:click="addFollowUp('closed')" wire:loading.attr="disabled"
                                wire:loading.remove>{{ __('public.submit') }}</button>
                        @else
                            <button type="submit" class="btn btn-info me-sm-2 me-1" wire:click="addFollowUp('step2')" wire:loading.attr="disabled"
                                wire:loading.remove>{{ __('public.submit') }}</button>
                        @endif
                        <button type="submit" class="btn btn-danger me-sm-2 me-1" wire:click="addFollowUp('not_answer')" wire:loading.attr="disabled" wire:loading.remove>عدم پاسخ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function myFunction(item) {
            @this.setUserId(item)
        }
    </script>
</div>
