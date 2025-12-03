@php
    use Illuminate\Support\Facades\Auth;
    use App\Constants\PermissionTitle;
@endphp
<div class="container-fluid flex-grow-1 container-p-y">
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('secretaries.page_title') }}</span>
            <a class="btn btn-info" href="{{ route('secretaries.create') }}">{{ __('secretaries.create') }}</a>
        </div>
        <div class="card-body row">
            <div class='col-md-4'>
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="{{ __('secretaries.search_placeholder') }}">
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
                    @foreach ($secretaries as $secretary)
                        <tr>
                            <td>{{ calcIterationNumber($secretaries, $loop) }}</td>
                            <td>{{ $secretary->user->fullName }}</td>
                            <td>{{ $secretary->user->mobile }}</td>
                            <td>{{ $secretary->user->province->name ?? '---' }}</td>
                            <td>{{ georgianToJalali($secretary->user->created_at, true) }}</td>
                            <td>
                                @if ($secretary->is_active)
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
                                <div class=" ">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('secretaries.edit', $secretary->id) }}"><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                        @if (Auth::user()->hasPermissionTo(PermissionTitle::ADD_PERMISSION_FOR_SECRETARY))
                                            <a type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#clerkPermissionsModal{{ $secretary->id }}">
                                                <i class="bx bx-lock-alt me-1"></i>
                                                دسترسی ها
                                            </a>
                                        @endif
                                        @if ($secretary->user->id !== Auth::user()->id and isAdminNumber())
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="{{ route('auth.login-by-user-id', $secretary->user->id) }}"><i class="bx bx-log-in me-1"></i> ورود با دسترسی کاربر</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="clerkPermissionsModal{{ $secretary->id }}" tabindex="-1" aria-labelledby="clerkPermissionsModalLabel{{ $secretary->id }}" aria-hidden="true"
                            wire:ignore.self>
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="clerkPermissionsModalLabel{{ $secretary->id }}">دسترسی های <span class="text-info">{{ $secretary->user->fullName }}</span></h5>
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
                                                            @if(in_array($permission->id, $secretary->user->permissions->pluck('id')->toArray()))
                                                                <li class="list-group-item p-1 cursor-pointer" wire:click="removePermission({{ $secretary->id }},{{ $permission->id }})">
                                                                    <input type="checkbox" checked>
                                                                    {{ __('permission_title.' . $permission->name) }}
                                                                </li>
                                                            @else
                                                                <li class="list-group-item p-1 cursor-pointer" wire:click="assignPermissions({{ $secretary->id }},{{ $permission->id }})">
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
                                                            @if(in_array($permission->id, $secretary->user->permissions->pluck('id')->toArray()))
                                                                <li class="list-group-item p-1 cursor-pointer" wire:click="removePermission({{ $secretary->id }},{{ $permission->id }})">
                                                                    <input type="checkbox" checked>
                                                                    {{ __('permission_title.' . $permission->name) }}
                                                                </li>
                                                            @else
                                                                <li class="list-group-item p-1 cursor-pointer" wire:click="assignPermissions({{ $secretary->id }},{{ $permission->id }})">
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
                                                            @if(in_array($permission->id, $secretary->user->permissions->pluck('id')->toArray()))
                                                                <li class="list-group-item p-1 cursor-pointer" wire:click="removePermission({{ $secretary->id }},{{ $permission->id }})">
                                                                    <input type="checkbox" checked>
                                                                    {{ __('permission_title.' . $permission->name) }}
                                                                </li>
                                                            @else
                                                                <li class="list-group-item p-1 cursor-pointer" wire:click="assignPermissions({{ $secretary->id }},{{ $permission->id }})">
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
                        <!-- Modal -->
                    @endforeach
                </tbody>
            </table>
            @if (count($secretaries) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $secretaries->links() }}</span>
        </div>
    </div>
</div>
