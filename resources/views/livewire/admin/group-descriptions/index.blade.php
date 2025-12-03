<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card p-3">
        @if (Session::has('success'))
            <div class="alert alert-success  mb-5 text-center">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger  mb-5 text-center">
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
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('group_descriptions.page_title') }}</span>
            <a class="btn btn-info" href="{{ route('group-descriptions.create') }}">{{ __('group_descriptions.create') }}</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label" for="search">{{ __('public.search') }}</label>
                    <input type="text" class="form-control" wire:model.live.debounce.1500ms="search" placeholder="{{ __('group_descriptions.search_all') }}">
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap mt-3">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('public.row_number') }}</th>
                        <th>{{ __('group_descriptions.professions') }}</th>
                        <th>{{ __('group_descriptions.description') }}</th>
                        <th>{{ __('public.created_at') }}</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($groupDescriptions as $key => $groupDescription)
                        <tr>
                            <td>{{ calcIterationNumber($groupDescriptions, $loop) }}</td>
                            <td>
                                @foreach ($groupDescription->professions as $profession)
                                    <span class="badge bg-primary">{{ $profession->title }}</span><br>
                                @endforeach
                            </td>
                            <td>
                                {{ Str::limit($groupDescription->description, 60) }}
                            </td>
                            <td style="min-width: 130px;"> {{ georgianToJalali($groupDescription->created_at) }} </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('group-descriptions.edit', [$groupDescription->id]) }}" data-bs-toggle="tooltip" data-bs-placement="top" class="icon-link btn btn-sm text-success"
                                        title="{{ __('public.edit') }}"><i class="tf-icons bx bx-edit-alt"></i></a>
                                    <button type="button" class="btn rounded-pill btn-icon btn-label-primary" wire:click="showProfessionDescription({{ $groupDescription }})"
                                        data-bs-target="#showProfessionDescription" data-bs-toggle="modal">
                                        <span class="tf-icons bx bx-show"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($groupDescriptions) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $groupDescriptions->links() }}</span>
        </div>
        <div class="modal fade" id="showProfessionDescription" tabindex="-1" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-xl modal-simple modal-add-new-address">
                <div class="modal-content p-1 p-md-1">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-1 mt-0 mt-md-n2">
                            <h5 class="address-title secondary-font m-0">{{ __('group_descriptions.page_title_show') }}</h3>
                        </div>
                        <label class="form-label" for="profession">{{ __('group_descriptions.professions') }}</label>
                        <div class="col-md-12 mb-3">
                            @foreach ($groupProfessionDescription?->professions ?? [] as $profession)
                                <span class="badge bg-primary">{{ $profession->title }}</span>
                            @endforeach
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="desc">{{ __('group_descriptions.description') }}</label>
                            <textarea name="description" id="description" class="form-control" rows="30" disabled>{{ $groupProfessionDescription?->description }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="table-responsive text-nowrap">
                                <table class="table  table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('courses.title') }}</th>
                                            <th>{{ __('courses.branch') }}</th>
                                            <th>ثبت نامی</th>
                                            <th>{{ __('courses.end_date') }}</th>
                                            <th>{{ __('courses.price') }}</th>
                                            <th>{{ __('courses.remaining_amount') }}</th>
                                            <th>{{ __('public.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach($groupProfessionDescriptionCourses ?? [] as $course)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    <a href="{{ route('courses.course-students', $course->id) }}">
                                                        {{ $course->title }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-label-primary">
                                                        {{ $course->branch?->name }}
                                                    </span>
                                                </td>
                                                <td>{{ $course->courseRegisters->count() }}</td>
                                                <td>{{ georgianToJalali($course->end_date) }}</td>
                                                <td>{{ number_format($course->price) }}</td>
                                                <td>
                                                    <span class="badge bg-label-danger">
                                                        {{ number_format($course->remainingAmount()) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <div class=" ">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{ route('courses.edit', $course->id) }}"><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                                                <a class="dropdown-item" href="{{ route('courses.course-students', $course->id) }}">
                                                                    <i class="bx bx-user me-1"></i> {{ __('students.page_title') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if(count($groupProfessionDescriptionCourses ?? []) === 0)
                                    <div class="text-center py-5">
                                        {{ __('messages.empty_table') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>