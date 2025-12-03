@extends('users.layouts.master')

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y  w-100">
        @include('users.layouts.alerts')
        <div class="card pb-3">
            <div class="align-items-center card-header d-flex justify-content-between">
                <span class="font-20 fw-bold heading-color">آزمون ها</span>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table  table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>دوره</th>
                            {{-- <th>مدت<sub>(دقیقه)</sub></th> --}}
                            <th>نام آزمون</th>
                            <th>نمره آزمون</th>
                            <th>تعداد سوال</th>
                            <th>ورود</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($courseRegisters as $index => $courseRegister)
                            @php
                                $exam = $courseRegister->course->profession?->exams()->first();
                            @endphp
                            <tr class="font-14 text-center">
                                <td><span>{{ $courseRegister->course->title }}</span></td>
                                <td><span>{!! $exam?->title ?? '<span class="badge bg-label-danger">آزمونی تعریف نشده</span>' !!}</span></td>
                                <td><span>{{ $exam?->passing_score ?? '---' }}</span></td>
                                <td><span>{{ $exam?->question_count ?? '---' }}</span></td>
                                <td>
                                    @if ($exam)
                                        <a class="btn btn-info btn-sm" href="{{ route('user.exams.show', ['exam' => $exam->id, 'courseRegister' => $courseRegister->id]) }}">
                                            <i class="bx bx-log-in-circle"></i>
                                        </a>
                                    @else
                                        ---
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                @if (count($courseRegisters) === 0)
                    <div class="text-center py-5">
                        {{ __('messages.empty_table') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
