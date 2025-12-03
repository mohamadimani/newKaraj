<div class="container-fluid flex-grow-1 container-p-y">
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">معرفی شده ها</span>
            <a href="{{ route('technicals.index') }}" class="btn btn-primary"> <i class="bx bx-arrow-back me-1"></i> درحال اقدام ها</a>
        </div>
        <div class="card-body">
            <div class="d-flex gap-2 col-md-12">
                <div class="col-md-3">
                    <label for="search">{{ __('public.search') }}</label> :
                    <input type="text" class="form-control " wire:model.live.debounce.500ms="search" placeholder="جستجو ...">
                </div>
                <div class="col-md-2" wire:ignore>
                    <label for="selectedSecretaryId">{{ __('clues.secretary') }}</label> :
                    <select class="form-control select2 " id="selectedSecretaryId" wire:model.live.debounce.500ms="selectedSecretaryId" onchange="myFunction()">
                        <option value="0">{{ __('public.all') }}</option>
                        @foreach($secretaries as $secretary)
                        <option value="{{ $secretary->id }}">{{ $secretary->user->fullName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="startDate">از</label> :
                    <input data-jdp type="text" class="form-control " wire:model.live.debounce.500ms="startDate" placeholder="تاریخ">
                    @include('admin.layouts.jdp', ['time' => false])
                </div>
                <div class="col-md-2">
                    <label for="endDate">تا</label> :
                    <input data-jdp type="text" class="form-control " wire:model.live.debounce.500ms="endDate" placeholder="تاریخ">
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('users.full_name') }}</th>
                        <th>{{ __('users.mobile') }}</th>
                        <th>{{ __('users.national_code') }}</th>
                        <th>آزمون کتبی</th>
                        <th>آزمون عملی</th>
                        <th>دوره</th>
                        <th>مشاور</th>
                        <th>توضیحات</th>
                        <th>بدهی</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($technicals as $technical)
                    <tr class="text-center">
                        <td>{{ calcIterationNumber($technicals, $loop) }}</td>
                        <td>{{ $technical->user->fullName }}</td>
                        <td>{{ $technical->user->mobile }}</td>
                        <td>{{ $technical->student->national_code }}</td>
                        <td>
                            @foreach ($technical->writtenExams as $index => $exam)
                            <span class="mb-2 d-block {{ $loop->iteration % 2 == 0 ? 'bg-label-dark' : 'bg-label-light text-dark' }}"><span class="fw-bold text-primary">کتبی {{ $index + 1 }} : </span>
                                {{ $exam->exam_date ??'-' }}</span>
                            @endforeach
                            @if($technical->writtenExams->count() === 0)
                            ---
                            @endif
                        </td>
                        <td>
                            @foreach ($technical->practicalExams as $index => $practicalExam)
                            <span class="mb-2 d-block  {{ $loop->iteration % 2 == 0 ? 'bg-label-dark' : 'bg-label-light text-dark' }}">
                                <span class="fw-bold text-warning">عملی {{ $index + 1 }} : </span>
                                @if($practicalExam->exam_date) {{ $practicalExam->exam_date }} @endif
                            </span>
                            @endforeach
                            @if($technical->writtenExams->count() >0)
                            <span class="btn btn-sm btn-info btn-sm p-1" wire:click="$set('technicalId', {{ $technical->id }})" data-bs-toggle="modal" data-bs-target="#practicalExamModal">
                                <i class=" bx bxs-watch"></i>
                            </span>
                            @endif
                            @if($technical->practicalExams->count() == 0 and $technical->writtenExams->count() == 0)
                            ---
                            @endif
                        </td>
                        <td>
                            @if ($technical->is_online_course)
                            {{ $technical->onlineCourse->name }} <span class="text-info">(دوره آنلاین)</span>
                            @else
                            {{ $technical->course->title }}
                            @endif
                        </td>
                        <td><span class=" bg-label-warning font-12">{{ $technical->courseRegister->secretary->full_name }}</span></td>
                        <td>
                            <i class="bg-label-primary text-black p-1 font-13 rounded-pill  ">{{ $technical->technicalDescriptions->count() }}</i> &nbsp;
                            <span class=" bg-label-info cursor-pointer" data-bs-toggle="modal" data-bs-target="#technical_description" wire:click="$set('technicalId', {{ $technical->id }})">
                                <i class="bx bx-notepad"></i>
                            </span>
                        </td>
                        <td class="text-danger">{{ number_format($technical->courseRegister->debt()) }}</td>
                        <td>
                            <div class=" ">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <span class="dropdown-item written-exam-button" wire:click="$set('technicalId', {{ $technical->id }})" data-bs-toggle="modal" data-bs-target="#writtenExamModal">
                                        <i class="text-primary bx bxs-watch me-1"></i>تاریخ آزمون کتبی</span>
                                    <hr class="p-0 m-0">
                                    <a class="dropdown-item" wire:click="updateStatusToProcessing({{ $technical->id }})"><i class="text-info bx bx-redo me-1"></i>درحال اقدام</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($technicals) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $technicals->links() }}</span>
        </div>
    </div>
    @include('admin.technicals.modals.written-modal')
    @include('admin.technicals.modals.practical-modal')
    @include('admin.technicals.modals.description-modal')
</div>
