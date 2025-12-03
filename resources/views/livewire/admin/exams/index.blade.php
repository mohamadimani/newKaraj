<div class="container-fluid flex-grow-1 container-p-y  w-100">
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">آزمون ها</span>
            <a class="btn btn-info" href="{{ route('exams.create') }}">ایجاد آزمون</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" wire:model.live="search" placeholder="{{ __('public.search') }}">
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table  table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>حرفه</th>
                        <th>عنوان</th>
                        <th>توضیحات</th>
                        <th>شعبه</th>
                        <th>مدت<sub>(دقیقه)</sub></th>
                        <th>نمره قبولی</th>
                        <th>تعداد سوال</th>
                        <th>وضعیت</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($exams as $index => $exam)
                    <tr class="text-center">
                        <td class="text-center">{{ $exams->firstItem() + $index }}</td>
                        <td>
                            @foreach ($exam->professions as $profession)
                            <span>{{ $profession->title }}</span><br>
                            @endforeach
                        </td>
                        <td><span>{{ $exam->title }}</span></td>
                        <td><span>{{ $exam->description }}</span></td>
                        <td><span>{{ $exam->branch?->name }}</span></td>
                        <td><span>{{ $exam->duration_min }}</span></td>
                        <td><span>{{ $exam->passing_score }}</span></td>
                        <td><span>{{ $exam->question_count }}</span></td>
                        <td>
                            @if ($exam->is_active == true)
                            <span class="btn btn-success btn-sm" wire:click="updateStatus({{ $exam->id }}, 0)"> فعال</span>
                            @else
                            <span class="btn btn-danger btn-sm" wire:click="updateStatus({{ $exam->id }}, 1)"> غیر فعال</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <div class=" ">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('exams.question', $exam->id) }}" class="dropdown-item cursor-pointer text-success">
                                            <i class=" bx bx-question-mark me-2"></i>افزودن سوالات
                                        </a>
                                        <hr class="m-0 p-0">
                                        <a class="dropdown-item" href="{{ route('exams.edit', $exam->id) }}"><i class="bx bx-edit-alt text-info me-1"></i> ویرایش</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($exams) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $exams->links() }}</span>
        </div>
    </div>
</div>
