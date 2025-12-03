@php
    use App\Enums\CourseReserve\StatusEnum;
@endphp
<div class="container-fluid flex-grow-1 container-p-y">
    @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">{{ __('course_reserves.page_title') }}</span>
            <a class="btn btn-info" href="{{ route('course-reserves.create') }}">{{ __('course_reserves.create') }}</a>
        </div>
        <div class="card-body">
            @include('admin.layouts.filters')
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="padding-right: 0;">{{ __('course_reserves.user') }}</th>
                        <th style="padding-right: 0;">موبایل</th>
                        <th style="padding-right: 0;">{{ __('course_reserves.profession') }}</th>
                        <th style="padding-right: 0;">{{ __('course_reserves.secretary') }}</th>
                        <th style="padding-right: 0;">{{ __('course_reserves.paid_amount') }}</th>
                        <th style="padding-right: 0;">توضیحات</th>
                        <th style="padding-right: 0;">{{ __('public.created_at') }}</th>
                        <th style="padding-right: 0;">{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($courseReserves as $courseReserve)
                        <tr class="text-center">
                            <td>{{ calcIterationNumber($courseReserves, $loop) }}</td>
                            <td style="padding-right: 0;">
                                <span class="">
                                    {{ $courseReserve->clue->user->fullName }}
                                </span>
                            </td>
                            <td style="padding-right: 0;">
                                <span>
                                    {{ $courseReserve->clue->user->mobile }}
                                </span>
                            </td>
                            <td style="padding-right: 0;">
                                <span>
                                    {{ $courseReserve->profession->title }}
                                </span>
                            </td>
                            <td style="padding-right: 0;">
                                <span class="badge bg-label-secondary">
                                    {{ $courseReserve->secretary->user->fullName }}
                                </span>
                            </td>
                            <td style="padding-right: 0;">{{ number_format($courseReserve->paid_amount) }}</td>
                            <td style="padding-right: 0;white-space: break-spaces;min-width: 150px">{{ $courseReserve->description }}</td>
                            <td style="padding-right: 0;">
                                {{ georgianToJalali($courseReserve->created_at, true) }} <br>
                                <span class="badge bg-label-warning"> {{ $courseReserve->createdBy->fullName }} </span>
                            </td>
                            <td style="padding-right: 0;">
                                <div class="d-flex gap-2">
                                    @if ($courseReserve->status === StatusEnum::PENDING)
                                        <button type="button" data-user-id="{{ $courseReserve->clue->user_id }}" class="btn rounded-pill btn-icon btn-label-warning add-follow-up-button"
                                            data-bs-toggle="modal" data-bs-target="#addFollowUpModal">
                                            <span class="tf-icons bx bx-time-five" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('follow_ups.add_follow_up') }}"></span>
                                        </button>
                                        <a href="{{ route('follow-ups.index', ['user_id' => $courseReserve->clue->user_id, 'back_url' => route('course-reserves.index')]) }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" class="icon-link" title="{{ __('follow_ups.see_follow_ups') }}">
                                            <button type="button" class="btn rounded-pill btn-icon btn-label-danger">
                                                <i class="tf-icons bx bx-timer"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('course-reserves.convert-to-course-view', $courseReserve->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" class="icon-link"
                                            title="{{ __('course_reserves.convert_to_course') }}">
                                            <button type="button" class="btn rounded-pill btn-icon btn-label-primary">
                                                <i class="tf-icons fa-solid fa-rectangle-list"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('payments.index', ['user_id' => $courseReserve->clue->user_id, 'back_url' => route('course-reserves.index', [], false)]) }}"
                                            class="icon-link">
                                            <button type="button" class="btn rounded-pill btn-icon btn-label-primary">
                                                <i class="tf-icons text-success fa-solid fa-money-bill me-1"></i>
                                            </button>
                                        </a>
                                    @else
                                        <span>---</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($courseReserves) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $courseReserves->links() }}</span>
        </div>
    </div>
    @include('components.modals.add-follow-up-modal')
</div>
