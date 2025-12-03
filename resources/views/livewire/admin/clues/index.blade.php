<div class="container-fluid flex-grow-1 container-p-y">
    <style>
        tr th,
        tr td {
            padding: 5px 0 !important;
        }
    </style>
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('clues.page_title') }}</span>
            <a class="btn btn-info btn-sm" href="{{ route('clues.create') }}">{{ __('clues.create') }}</a>
        </div>
        <div class="card-body">
            @include('admin.layouts.filters', ['perofession' => true])
        </div>
        <div class="d-flex align-items-center justify-content-end mb-3">
            @if (count($selectedClues) > 0)
                <div class="alert alert-info m-2" style="padding-top: 6px; padding-bottom: 3px;">
                    <span>{{ __('clues.selected_clues_for_sms_alert', ['count' => count($selectedClues)]) }}</span>
                </div>
            @endif
            <button class="btn btn-primary m-2 send-group-sms-button" data-bs-toggle="modal" data-bs-target="#sendGroupSmsModal" id="send-multi-sms"
                {{ count($selectedClues) === 0 ? 'disabled' : '' }}>
                {{ __('clues.send_multi_sms') }}
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="text-center font-13">
                        <th>
                            @if ($selectAllClues == 'yes')
                                <span class="btn btn-sm btn-warning" wire:click="$set('selectAllClues','no')">عدم انتخاب همه</span>
                            @else
                                <span class="btn btn-sm btn-primary" wire:click="$set('selectAllClues','yes')">انتخاب همه</span>
                            @endif
                        </th>
                        <th>{{ __('users.full_name') }}</th>
                        <th>{{ __('users.mobile') }}</th>
                        <th>{{ __('users.province') }}</th>
                        <th>{{ __('clues.familiarity_ways') }}</th>
                        <th>{{ __('clues.secretary') }}</th>
                        <th>{{ __('clues.favorite_professions') }}</th>
                        <th>کارآموز</th>
                        <th>پیگیری</th>
                        <th>کیف پول</th>
                        <th>{{ __('public.created_at') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($clues as $clue)
                        <tr class="text-center font-13">
                            <td>
                                {{ calcIterationNumber($clues, $loop) }}
                                &nbsp;
                                @if (in_array($clue->id, $selectedClues))
                                    <span class="btn btn-sm btn-warning" wire:click="unsetSelectedClueId({{ $clue->id }})">عدم انتخاب</span>
                                @else
                                    <span class="btn btn-sm btn-primary" wire:click="setSelectedClueId({{ $clue->id }})">انتخاب</span>
                                @endif
                            </td>
                            <td>{{ $clue?->user?->fullName }}</td>
                            <td>{{ $clue?->user?->mobile }}</td>
                            <td>{{ $clue?->user?->province?->name ?? '---' }}</td>
                            <td>
                                <span class="badge bg-label-secondary">
                                    {{ $clue->familiarityWay->title ?? '---' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-warning">
                                    {{ $clue->secretary->user->fullName ?? $clue->createdBy() }}
                                </span>
                            </td>
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
                            <td>{!! $clue?->student_id > 0 ? '<span class="badge bg-label-success">بله</span>' : '<span class="badge bg-label-danger">خیر</span>' !!}</td>
                            <td> {{ $clue?->user?->followUps->count() }}</td>
                            <td> <span class="badge bg-label-success">{{ $clue?->user ? number_format($clue?->user?->wallet) : 0 }}</span></td>
                            <td>{{ georgianToJalali($clue?->created_at, true) }}</td>
                            <td>
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('course-registers.create', ['clue_id' => $clue->id, 'back_url' => route('clues.index', [], false)]) }}">
                                        <i class="tf-icons bx bx-plus"></i> {{ __('clues.courses') }}
                                    </a>
                                    <a class="dropdown-item text-info" href="{{ route('online-course-baskets.show', [$clue?->user?->id ?? 1]) }}">
                                        <i class="tf-icons bx bx-plus"></i> دوره های آنلاین
                                    </a>
                                    <a class="dropdown-item" href="{{ route('course-reserves.create', ['clue_id' => $clue->id, 'back_url' => route('clues.index', [], false)]) }}">
                                        <i class="tf-icons bx bx-spreadsheet"></i> {{ __('clues.reserve_course') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('clues.edit', $clue->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> {{ __('clues.edit') }}
                                    </a>
                                    <span data-user-id="{{ $clue->user_id }}" class="dropdown-item cursor-pointer add-follow-up-button" data-bs-toggle="modal" data-bs-target="#addFollowUpModal">
                                        <i class="tf-icons bx bx-time-five"></i> {{ __('follow_ups.add_follow_up') }}
                                    </span>
                                    <a class="dropdown-item" href="{{ route('follow-ups.index', ['user_id' => $clue->user_id, 'back_url' => route('clues.index', [], false)]) }}">
                                        <i class="tf-icons bx bx-timer"></i> {{ __('follow_ups.see_follow_ups') }}
                                    </a>
                                    @if ($clue->user?->id !== Auth::user()->id and isAdminNumber())
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="{{ route('auth.login-by-user-id', [$clue->user?->id ?? 1]) }}"><i class="bx bx-log-in me-1"></i> ورود با دسترسی
                                            کاربر</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($clues) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $clues->links() }}</span>
        </div>
    </div>
    @include('components.modals.add-follow-up-modal')
    @include('components.modals.send-gruop-sms-modal')
</div>
