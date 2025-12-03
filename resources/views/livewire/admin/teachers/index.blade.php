<div class="container-fluid flex-grow-1 container-p-y">
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('teachers.page_title') }}</span>
            <a class="btn btn-info" href="{{ route('teachers.create') }}">{{ __('teachers.create') }}</a>
        </div>
        <div class="card-body row">
            <div class='col-md-4'>
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="{{ __('teachers.search_placeholder') }}">
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('users.full_name') }}</th>
                        <th>{{ __('users.mobile') }}</th>
                        <th>{{ __('users.province') }}</th>
                        <th>{{ __('users.created_at') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($teachers as $teacher)
                    <tr>
                        <td>{{ calcIterationNumber($teachers, $loop) }}</td>
                        <td>{{ $teacher->user->fullName }}</td>
                        <td>{{ $teacher->user->mobile }}</td>
                        <td>{{ $teacher->user->province->name ?? '---' }}</td>
                        <td>{{ georgianToJalali($teacher->user->created_at, true) }}</td>
                        <td>
                            @if ($teacher->is_active)
                            <span class="badge bg-label-success me-1">
                                {{ __('public.status_active') }}
                            </span>
                            @else
                            <span class="badge bg-label-danger me-1">
                                {{ __('public.status_inactive') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class=" ">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if ($teacher->is_active)
                                    <a class="dropdown-item text-danger" wire:click="updateStatus({{ $teacher->id }}, 0)"><i class="bx bx-x me-1"></i> {{ __('public.status_inactive') }}</a>
                                    @else
                                    <a class="dropdown-item text-success" wire:click="updateStatus({{ $teacher->id }}, 1)"><i class="bx bx-check me-1"></i>
                                        {{ __('public.status_active') }}</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('teachers.edit', $teacher->id) }}"><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                    @if ($teacher->user->id !== Auth::user()->id and isAdminNumber())
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('auth.login-by-user-id', $teacher->user->id) }}"><i class="bx bx-log-in me-1"></i> ورود با دسترسی کاربر</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($teachers) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $teachers->links() }}</span>
        </div>
    </div>
</div>
