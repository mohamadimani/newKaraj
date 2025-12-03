<div class="container-fluid flex-grow-1 container-p-y  w-100">
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('courses.page_title') }}</span>
            <a class="btn btn-info" href="{{ route('courses.create') }}">{{ __('courses.create') }}</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" wire:model.live="search" placeholder="{{ __('public.search') }}">
                </div>
                <div class="col-md-3" wire:ignore>
                    <select class="form-select select2" wire:model.live="teacher_id" id="selectedTeacherId" onchange="myFunction()">
                        <option value="">انتخاب استاد</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->user->fullName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="courseListShowType" id="courseListShowType">
                        <option value="all">همه</option>
                        <option value="notStart">دوره های جاری</option>
                        <option value="end">دوره های تمام شده</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <input data-jdp type="text" class="form-control " wire:model.live="startDate" placeholder="تاریخ شروع">
                    @include('admin.layouts.jdp', ['time' => false])
                </div>
                <div class="col-md-1">
                    <input data-jdp type="text" class="form-control " wire:model.live="endDate" placeholder="تاریخ پایان">
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table  table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('courses.title') }}</th>
                        <th>{{ __('courses.capacity') }}</th>
                        <th>ثبت نامی</th>
                        <th>{{ __('courses.teacher') }}</th>
                        <th>{{ __('courses.start_date') }}</th>
                        <th>{{ __('courses.end_date') }}</th>
                        <th>شهریه<sub>(تومان)</sub></th>
                        <th> جمع پرداختی <sub>(تومان)</sub></th>
                        <th>مانده<sub>(تومان)</sub></th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($courses as $index => $course)
                    <tr class="text-center">
                        <td class="text-center">{{ $courses->firstItem() + $index }}</td>
                        <td><a href="{{ route('courses.course-students',$course->id) }}">{{ $course->title }}</a></td>
                        <td>{{ $course->capacity }}</td>
                        <td>{{ $course->courseRegisters->count() }}</td>
                        <td>
                            <span class="badge bg-label-info">
                                {{ $course->teacher?->user->fullName }}
                            </span>
                        </td>
                        <td>{{ georgianToJalali($course->start_date) }}</td>
                        <td>{{ georgianToJalali($course->end_date) }}</td>
                        <td>{{ number_format($course->price) }}</td>
                        <td>{{ number_format($course->paidAmountSum()) }}</td>
                        <td>
                            @php
                            $remainingAmount = $course->remainingAmount();
                            @endphp

                            <span class="badge {{ $remainingAmount > 0 ? 'bg-label-danger' : 'bg-label-success'}} ">
                                {{ number_format($remainingAmount) }}
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
            @if(count($courses) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $courses->links() }}</span>
        </div>
    </div>
    <script>
        function myFunction() {
            const value = $('select#selectedTeacherId').val();
            @this.selectedTeacherId(value)
        }
        myFunction()
    </script>
</div>
