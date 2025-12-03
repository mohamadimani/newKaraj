@php
    use Illuminate\Support\Facades\Auth;
    use App\Constants\PermissionTitle;
@endphp
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('clerks.page_title') }}</span>
            <a class="btn btn-info" href="{{ route('clerks.create') }}">{{ __('clerks.create') }}</a>
        </div>
        @include('admin.layouts.alerts')
        <div class="card-body row">
            <div class='col-md-4'>
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="{{ __('clerks.search_placeholder') }}">
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('users.full_name') }}</th>
                        <th>{{ __('users.mobile') }}</th>
                        <th>{{ __('clerks.branch') }}</th>
                        <th>{{ __('users.created_at') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($clerks as $clerk)
                        <tr>
                            <td>{{ calcIterationNumber($clerks, $loop) }}</td>
                            <td>{{ $clerk->user->fullName }}</td>
                            <td>{{ $clerk->user->mobile }}</td>
                            <td>{{ $clerk->branch->name ?? '---' }}</td>
                            <td>{{ georgianToJalali($clerk->user->created_at, true) }}</td>
                            <td>
                                @if ($clerk->is_active)
                                    <span class="badge bg-label-primary me-1">
                                        {{ __('public.status_active') }}
                                    </span>
                                @else
                                    <span class="badge bg-label-danger me-1">
                                        {{ __('public.status_inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('clerks.edit', $clerk->id) }}"><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                        @if (Auth::user()->hasPermissionTo(PermissionTitle::ADD_PERMISSION_FOR_CLERK))
                                            <a type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#clerkPermissionsModal{{ $clerk->id }}">
                                                <i class="bx bx-lock-alt me-1"></i>
                                                دسترسی ها
                                            </a>
                                        @endif
                                        @if ($clerk->user->id !== Auth::user()->id and isAdminNumber())
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="{{ route('auth.login-by-user-id', $clerk->user->id) }}"><i class="bx bx-log-in me-1"></i> ورود با دسترسی کاربر</a>
                                        @endif
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="clerkPermissionsModal{{ $clerk->id }}" tabindex="-1" aria-labelledby="clerkPermissionsModalLabel{{ $clerk->id }}" aria-hidden="true"
                                        wire:ignore.self>
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="clerkPermissionsModalLabel{{ $clerk->id }}">دسترسی های <span class="text-info">{{ $clerk->user->fullName }}</span></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @include('admin.layouts.alerts')
                                                    <style>
                                                        .cursor-pointer {
                                                            cursor: pointer !important;
                                                        }
                                                    </style>
                                                    @if ($permissions->count() > 0)
                                                        <div class="row font-13">
                                                            <div class="col-4">
                                                                <ul class="list-group">
                                                                    @foreach ($permissions->take(ceil($permissions->count() / 3)) as $permission)
                                                                        @if(in_array($permission->id, $clerk->user->permissions->pluck('id')->toArray()))
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="removePermission({{ $clerk->id }},{{ $permission->id }})">
                                                                                <input type="checkbox" checked>
                                                                                {{ __('permission_title.' . $permission->name) }}
                                                                            </li>
                                                                        @else
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="assignPermissions({{ $clerk->id }},{{ $permission->id }})">
                                                                                <input type="checkbox">
                                                                                {{ __('permission_title.' . $permission->name) }}
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                            <div class="col-4">
                                                                <ul class="list-group">
                                                                    @foreach ($permissions->skip(ceil($permissions->count() / 3))->take(ceil($permissions->count() / 3)) as $permission)
                                                                        @if(in_array($permission->id, $clerk->user->permissions->pluck('id')->toArray()))
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="removePermission({{ $clerk->id }},{{ $permission->id }})">
                                                                                <input type="checkbox" checked>
                                                                                {{ __('permission_title.' . $permission->name) }}
                                                                            </li>
                                                                        @else
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="assignPermissions({{ $clerk->id }},{{ $permission->id }})">
                                                                                <input type="checkbox">
                                                                                {{ __('permission_title.' . $permission->name) }}
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                            <div class="col-4">
                                                                <ul class="list-group ">
                                                                    @foreach ($permissions->skip(ceil($permissions->count() / 3) * 2) as $permission)
                                                                        @if(in_array($permission->id, $clerk->user->permissions->pluck('id')->toArray()))
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="removePermission({{ $clerk->id }},{{ $permission->id }})">
                                                                                <input type="checkbox" checked>
                                                                                {{ __('permission_title.' . $permission->name) }}
                                                                            </li>
                                                                        @else
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="assignPermissions({{ $clerk->id }},{{ $permission->id }})">
                                                                                <input type="checkbox">
                                                                                {{ __('permission_title.' . $permission->name) }}
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p>این شخص هیچ دسترسی ندارد</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($clerks) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $clerks->links() }}</span>
        </div>
    </div>
</div>
