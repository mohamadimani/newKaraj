<div class="container-fluid flex-grow-1 container-p-y">
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('class_rooms.page_title') }}</span>
            <a class="btn btn-info" href="{{ route('class-rooms.create') }}">{{ __('class_rooms.create') }}</a>
        </div>
        <div class="card-body row">
            <div class='col-md-4'>
                <input type="text" class="form-control" wire:model.live.debounce.1000ms="search" placeholder="جستجو">
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('branches.name') }}</th>
                        <th>{{ __('class_rooms.name') }}</th>
                        <th>{{ __('class_rooms.number') }}</th>
                        <th>{{ __('class_rooms.professions') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($classRooms as $classRoom)
                        <tr>
                            <td>{{ calcIterationNumber($classRooms, $loop) }}</td>
                            <td>{{ $classRoom->branch?->name }}</td>
                            <td>{{ $classRoom->name }}</td>
                            <td>{{ $classRoom->number }}</td>
                            <td>
                                @foreach ($classRoom->professions as $profession)
                                    <span class="badge bg-label-primary me-1">{{ $profession->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($classRoom->is_active)
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
                                        <a class="dropdown-item cursor-pointer text-primary" href="{{ route('class-rooms.edit', $classRoom->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> {{ __('public.edit') }}
                                        </a>

                                        @if ($classRoom->is_active)
                                            <button class="dropdown-item cursor-pointer text-danger" wire:click="updateStatus({{ $classRoom->id }}, 0)">
                                                <i class="bx bx-power-off me-1"></i> {{ __('class_rooms.inactive') }}
                                            </button>
                                        @else
                                            <button class="dropdown-item cursor-pointer text-success" wire:click="updateStatus({{ $classRoom->id }}, 1)">
                                                <i class="bx bx-power-off me-1"></i> {{ __('class_rooms.active') }}
                                            </button>
                                        @endif

                                        {{-- <button wire:click="deleteClassRoom({{ $classRoom->id }})" class="dropdown-item cursor-pointer text-danger">
                                            <i class="bx bx-trash me-1"></i> {{ __('public.delete') }}
                                        </button> --}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (!count($classRooms))
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            {{ $classRooms->links() }}
        </div>
    </div>
</div>
