<div class="container-fluid flex-grow-1 container-p-y">
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card ">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color"> دسترسی ها</span>
        </div>
        <div class="card-body border-bottom ">
            <div class="d-flex justify-content-between mb-3">
                <div class="col-md-9">
                    <input type="text" class="form-control " wire:model.live="search" placeholder="{{ __('public.search') }}">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary float-end" wire:click="syncPermissions" title="بروزرسانی دسترسی ها">
                        <i class="fa-solid fa-sync"></i>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        @if ($permissions->count() > 0)
                            @foreach ($permissions->chunk(4) as $chunk)
                                <tr>
                                    @foreach ($chunk as $permission)
                                        <td>{{ __('permission_title.' . $permission->name) }}</td>
                                    @endforeach
                                    @for ($i = $chunk->count(); $i < 4; $i++)
                                        <td></td>
                                    @endfor
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">{{ __('public.no_data_found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
