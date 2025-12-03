<div class="container-fluid flex-grow-1 container-p-y">
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card ">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color"> نقش ها</span>
        </div>

        <div class="card-body border-bottom">
            <form class="mb-3">
                <div class="row d-flex ">
                    <label class="form-label">نام نقش</label>
                    <input type="text" class="form-control" wire:model="role_name" placeholder="نام نقش را وارد کنید">
                    @error('role_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <button type="submit" class="btn btn-primary" wire:click.prevent="store">ذخیره</button>
                </div>
            </form>
        </div>

        <div class="card-body border-bottom ">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>نام نقش</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rolePersianNameArray = [
                                'clerk' => 'کارمند',
                                'clue' => 'سرنخ',
                                'teacher' => 'استاد',
                                'secretary' => 'مشاور',
                                'admin' => 'مدیر',
                            ];
                        @endphp
                        @foreach ($roles as $role)
                            <tr>
                                @if ($role_id_edit == $role->id)
                                    <td><input type="text" class="form-control" wire:model="role_name_edit" placeholder="نام نقش را وارد کنید">
                                        @error('role_name_edit')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td><span wire:click="update({{ $role->id }})" class="btn btn-success btn-sm">ذخیره</span></td>
                                @else
                                    <td>{{ $rolePersianNameArray[$role->name] }}</td>
                                    <td><span wire:click="edit({{ $role->id }})" class="btn btn-primary btn-sm">ویرایش</span></td>
                                @endif
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#rolePermissionsModal{{ $role->id }}">
                                        نمایش دسترسی ها
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="rolePermissionsModal{{ $role->id }}" tabindex="-1" aria-labelledby="rolePermissionsModalLabel{{ $role->id }}"
                                        aria-hidden="true" wire:ignore.self>
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">

                                                    <h5 class="modal-title" id="rolePermissionsModalLabel{{ $role->id }}">دسترسی های نقش {{ $rolePersianNameArray[$role->name] }}</h5>
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
                                                                        @if (in_array($permission->id, $role->permissions->pluck('id')->toArray()))
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="removePermission({{ $role->id }},{{ $permission->id }})">
                                                                                <input type="checkbox" checked>
                                                                                {{ __('permission_title.' . $permission->name) }}
                                                                            </li>
                                                                        @else
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="assignPermissions({{ $role->id }},{{ $permission->id }})">
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
                                                                        @if (in_array($permission->id, $role->permissions->pluck('id')->toArray()))
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="removePermission({{ $role->id }},{{ $permission->id }})">
                                                                                <input type="checkbox" checked>
                                                                                {{ __('permission_title.' . $permission->name) }}
                                                                            </li>
                                                                        @else
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="assignPermissions({{ $role->id }},{{ $permission->id }})">
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
                                                                        @if (in_array($permission->id, $role->permissions->pluck('id')->toArray()))
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="removePermission({{ $role->id }},{{ $permission->id }})">
                                                                                <input type="checkbox" checked>
                                                                                {{ __('permission_title.' . $permission->name) }}
                                                                            </li>
                                                                        @else
                                                                            <li class="list-group-item p-1 cursor-pointer" wire:click="assignPermissions({{ $role->id }},{{ $permission->id }})">
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function myFunction(id) {
            const value = $('select#permissions_id_' + id).val();
            console.log(value);
            @this.setPermissionId(value)
        }
    </script>
</div>
