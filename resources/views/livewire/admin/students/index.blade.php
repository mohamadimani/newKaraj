@php
    use App\Models\Course;
    use App\Models\CourseRegister;
    use App\Enums\CourseRegister\StatusEnum;
@endphp
<div class="container-fluid flex-grow-1 container-p-y">
    @include('admin.layouts.alerts')
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            @if (request()->query('course_id'))
                @php
                    $course = Course::find(request()->query('course_id'));
                @endphp
                <span class="font-20">{!! __('students.students_of_course', ['course' => '<b class="text-primary">' . $course->title . '</b>']) !!}</span>
            @else
                <span class="font-20 fw-bold heading-color">{{ __('students.page_title') }}</span>
            @endif
            @if (request()->query('back_url'))
                <div class="card-header-actions">
                    <a href="{{ request()->query('back_url') }}" class="btn btn-label-primary mb-3">
                        <i class="tf-icons fa-solid fa-arrow-right-from-bracket"></i>
                        {{ __('public.back') }}
                    </a>
                </div>
            @endif
        </div>
        <div class="card-body">
            @include('admin.layouts.filters')
        </div>
        <style>
            tr th,
            tr td {
                padding: 5px 0 !important;
            }
        </style>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th style="padding: 0 8px !important;">#</th>
                        <th>{{ __('users.full_name') }}</th>
                        <th>{{ __('users.mobile') }}</th>
                        <th>کیف پول</th>
                        <th>{{ __('clues.familiarity_ways') }}</th>
                        <th>{{ __('students.secretaries') }}</th>
                        <th>{{ __('students.registered_courses') }}</th>
                        <th>مجموع پرداخت ها <sub>(تومان)</sub></th>
                        <th>مجموع بدهی ها <sub>(تومان)</sub></th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($students as $student)
                        @php
                            $courseRegisters = $student->courseRegisters;
                            if ($courseRegisters->whereIn('status', [StatusEnum::REGISTERED, StatusEnum::TECHNICAL])->count() == 0) {
                                continue;
                            }
                        @endphp
                        <tr class="text-center">
                            <td>
                                {{ calcIterationNumber($students, $loop) }}
                            </td>
                            <td><a href="{{ route('students.edit', [$student->id]) }}">{{ $student->user->fullName }}</a></td>
                            <td>{{ $student->user->mobile }}</td>
                            <td> <span class="badge bg-label-success">{{ $student?->user ? number_format($student?->user?->wallet) : 0 }}</span></td>
                            <td>
                                <span class="badge bg-label-secondary">
                                    {{ $student->user->clue->familiarityWay->title ?? '---' }}
                                </span>
                            </td>
                            <td>
                                <ul class="p-0 m-0">
                                    @foreach ($courseRegisters as $courseRegister)
                                        <li class="badge bg-label-secondary">{{ $courseRegister->secretary?->user->fullName }}</li>
                                        <br>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="p-0 m-0">
                                    @foreach ($courseRegisters->whereIn('status', [StatusEnum::REGISTERED, StatusEnum::TECHNICAL]) as $courseRegister)
                                        <li class="badge">
                                            <span class="badge bg-label-secondary font-12">
                                                {{ $courseRegister->course->title }}
                                            </span>
                                            @if ($courseRegister->status == StatusEnum::TECHNICAL)
                                                <span class="bg-label-warning font-11">فنی حرفه ای</span>
                                            @endIf
                                        </li>
                                        <br>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-success">
                                <ul class="p-0 m-0">
                                    @foreach ($courseRegisters as $courseRegister)
                                        <li class="badge bg-label-success">{{ number_format($courseRegister->paid_amount) }}</li>
                                        <br>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-danger">
                                <ul class="p-0 m-0">
                                    @foreach ($courseRegisters as $courseRegister)
                                        @if ($courseRegister->debt() > 0)
                                            <li class="badge bg-label-danger">{{ number_format($courseRegister->debt()) }}</li>
                                            <br>
                                        @else
                                            <li class="badge bg-label-info">{{ __('payments.settled') }}</li>
                                            <br>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            <td class='text-center'>
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('course-registers.create', ['clue_id' => $student->user->clue->id, 'back_url' => route('students.index', [], false)]) }}"
                                        class="dropdown-item cursor-pointer">
                                        <i class="bx bx-plus me-1 text-primary"></i> {{ __('clues.courses') }}
                                    </a>
                                    <a class="dropdown-item " href="{{ route('online-course-baskets.show', [$student->user->id]) }}">
                                        <i class="tf-icons bx bx-plus text-success"></i> دوره های آنلاین
                                    </a>
                                    <a href="{{ route('payments.index', ['user_id' => $student->user_id, 'back_url' => route('students.index', [], false)]) }}" class="dropdown-item cursor-pointer">
                                        <i class="fa-solid fa-money-bill me-1 text-primary"></i> {{ __('payments.page_title') }}
                                    </a>
                                    <span class="dropdown-item cursor-pointer add-follow-up-button" data-user-id="{{ $student->user_id }}" data-bs-toggle="modal" data-bs-target="#addFollowUpModal">
                                        <i class="bx bx-time-five me-1 text-warning"></i> {{ __('follow_ups.add_follow_up') }}
                                    </span>
                                    <a href="{{ route('follow-ups.index', ['user_id' => $student->user_id, 'back_url' => route('students.index', [], false)]) }}" class="dropdown-item cursor-pointer">
                                        <i class="bx bx-timer me-1 text-danger"></i> {{ __('follow_ups.see_follow_ups') }}
                                    </a>
                                    <span class="dropdown-item cursor-pointer" data-bs-toggle="modal" data-bs-target="#sent-sms-log-modal" onclick="setUser({{ $student->user_id }})">
                                        <i class="bx bx-message me-1 text-info"></i>پیام های ارسال شده
                                    </span>
                                    @if ($student->user->id !== Auth::user()->id and isAdminNumber())
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="{{ route('auth.login-by-user-id', $student->user->id) }}"><i class="bx bx-log-in me-1"></i> ورود با دسترسی کاربر</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($students) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $students->links() }}</span>
        </div>
    </div>

    @include('components.modals.add-follow-up-modal')
    @include('admin.students.modals.sent-sms-modal')
    <style>
        .icon-link:hover {
            color: #fff;
        }

        table th,
        table td {
            padding-right: 0px !important;
        }

        table th,
        table td {
            padding-right: 0 !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#paid-amount').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                if (this.value) {
                    this.value = new Intl.NumberFormat().format(this.value);
                    const numericValue = parseInt(this.value.replace(/,/g, ''));
                    const persianText = numberToPersianText(numericValue);
                    $('#persian-number').text(persianText + ' تومان');
                } else {
                    $('#persian-number').text('');
                }
            });

            $('#payment-course-id').on('change', function() {
                $('#paymentable_id').val($(this).find(":selected").val())
            });
        });

        function setUser(userId) {
            @this.setUser(userId);
        }
    </script>
</div>
