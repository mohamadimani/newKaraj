<div class="container-fluid flex-grow-1 container-p-y">
    @if (Session::has('success'))
        <div class="alert alert-success text-center">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card pb-3">
        @if (Session::has('error'))
            <div class="alert alert-danger mb-5 text-center">
                {{ Session::get('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger ">
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li class="font-13">* {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <span class="font-20 fw-bold heading-color p-3">{{ __('professions.page_title') }}</span>
        <div class="align-items-center card-header d-flex justify-content-between">

            <div class='col-md-4'>
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="{{ __('secretaries.search_placeholder') }}">
            </div>

            <a class="btn btn-info" href="{{ route('professions.create') }}">{{ __('professions.create') }}</a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>عنوان</th>
                        <th>{{ __('professions.public_price') }}</th>
                        <th>{{ __('professions.public_duration_hours') }}</th>
                        <th>{{ __('professions.public_capacity') }}</th>
                        <th>{{ __('professions.branches') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th>{{ __('public.created_at') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($professions as $key => $profession)
                        <tr>
                            <td>{{ calcIterationNumber($professions, $loop) }}</td>
                            <td>{{ $profession->title }}</td>
                            <td>{{ $profession->public_price ? number_format($profession->public_price) : '---' }}</td>
                            <td>{{ $profession->public_duration_hours ? $profession->public_duration_hours : '---' }}</td>
                            <td>{{ $profession->public_capacity ? $profession->public_capacity : '---' }}</td>
                            <td>
                                @foreach ($profession->branches as $branch)
                                    <span class="badge bg-label-primary me-1">{{ $branch->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($profession->is_active == 1)
                                    <span class="badge bg-label-success me-1">فعال</span>
                                @else
                                    <span class="badge bg-label-danger me-1">غیر فعال</span>
                                @endif
                            </td>
                            <td>{{ \Verta::instance($profession->created_at)->format('%d %B %Y') }} </td>
                            <td>
                                <div class=" ">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if ($profession->is_active)
                                            <span class="text-left text-warning dropdown-item cursor-pointer" wire:click='changeStatus({{ $profession->id }},0)'>
                                                <i class="bx bx-x me-1"></i><span>{{ __('professions.deactivate') }}</span>
                                            </span>
                                        @else
                                            <span class="text-left text-success dropdown-item cursor-pointer" wire:click='changeStatus({{ $profession->id }},1)'>
                                                <i class="bx bx-check me-1"></i><span>{{ __('professions.activate') }}</span>
                                            </span>
                                        @endif
                                        <a class="dropdown-item cursor-pointer" href="{{ route('professions.edit', $profession->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> {{ __('public.edit') }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (!count($professions))
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $professions->links() }}</span>
        </div>
    </div>
</div>