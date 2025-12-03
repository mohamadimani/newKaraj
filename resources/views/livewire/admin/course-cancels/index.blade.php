@php
use App\Enums\CourseRegister\StatusEnum;
use App\Models\CourseRegister;
@endphp
<div class="container-fluid flex-grow-1 container-p-y">
    @include('admin.layouts.alerts')

    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('course_cancels.page_title') }}</span>
        </div>
        <div class="card-body row">
            @include('admin.layouts.filters')
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('users.full_name') }}</th>
                        <th> موبایل </th>
                        <th>{{ __('course_cancels.course') }}</th>
                        <th>مشاور</th>
                        <th>{{ __('course_cancels.description') }}</th>
                        <th>{{ __('public.status') }}</th>
                        <th> پرداختی <sub>(تومان)</sub></th>
                        <th> باقی مانده <sub>(تومان)</sub></th>
                        <th>تاریخ</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($courseRegisters as $courseRegister)
                    <tr class="text-center">
                        <td>{{ calcIterationNumber($courseRegisters, $loop) }}</td>
                        <td>{{ $courseRegister->student->user->fullName }}</td>
                        <td>{{ $courseRegister->student->user->mobile }}</td>
                        <td> <a href="{{ route('courses.course-students', $courseRegister->course->id) }}">{{ $courseRegister->course->title }}</a> </td>
                        <td><span class="bg-label-warning">{{ $courseRegister->secretary->user->full_name ?? '--' }}</span></td>
                        <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $courseRegister->cancel_description }}">
                            {{ substr($courseRegister->cancel_description, 0, 21) . (strlen($courseRegister->cancel_description) > 20 ? '...' : '---') }}
                        </td>
                        <td> <span class="badge bg-label-{{ $courseRegister->status->getColor() }} me-1">{{ $courseRegister->status->getLabel() }}</span> </td>
                        <td class="text-success">{{ number_format($courseRegister->paid_amount) }}</td>
                        <td class="text-danger">
                            @php
                            $remainingAmount = $courseRegister->amount > 0 ? $courseRegister->amount - $courseRegister->paid_amount : $courseRegister->course->price - $courseRegister->paid_amount;
                            @endphp
                            @if ($remainingAmount > 0)
                            {{ number_format($remainingAmount) }}
                            @else
                            <span class="badge bg-label-info">{{ __('payments.settled') }}</span>
                            @endif
                        </td>
                        <td>{{ georgianToJalali($courseRegister->updated_at, true) }} <br>
                            <span class="badge bg-label-warning"> {{ $courseRegister->createdBy->fullName }} </span>
                        </td>
                        <td>
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('payments.index', ['course_register_id' => $courseRegister->id, 'back_url' => route('course-cancels.index', [], false)]) }}" class="dropdown-item">
                                    <i class="tf-icons text-success fa-solid fa-money-bill"></i> {{ __('payments.page_title') }}
                                </a>
                            </div>
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
        <div class="p-3">
            <span class="d-block mt-3">{{ $courseRegisters->links() }}</span>
        </div>
    </div>
</div>