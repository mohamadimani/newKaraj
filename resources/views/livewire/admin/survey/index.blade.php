<div class="container-fluid flex-grow-1 container-p-y">
    @php
    use Illuminate\Support\Facades\Auth;
    use App\Constants\PermissionTitle;
    @endphp
    <div class="card pb-3">
        <div class="align-items-center card-header  justify-content-between">
            <span class="font-20 fw-bold heading-color">لیست ثبت کنندگان نظر</span>
        </div>
        <div class="card-body row">
            @include('admin.layouts.alerts')
            @include('admin.layouts.filters' , ['secretaries' => false, 'teachersShow' => true])
        </div>
        <style>
            .modal-body * {
                white-space: normal !important;
            }

            h6 {
                margin-bottom: 2px !important;
                font-size: 14px !important;
            }
        </style>
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>{{ __('users.full_name') }}</th>
                        <th>{{ __('course_registers.course') }}</th>
                        <th>استاد</th>
                        <th>امتیاز</th>
                        <th>تاریخ ثبت </th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($survey as $surveyItem)
                    <tr class="text-center">
                        <td> {{ calcIterationNumber($survey, $loop) }} </td>
                        <td><a href="{{ route('students.edit', [$surveyItem->user->student->id]) }}">{{ $surveyItem->user->fullName }}</a></td>
                        <td><a href="{{ route('courses.course-students', $surveyItem->courseRegister?->course?->id) }}">{{ $surveyItem->courseRegister->course->title }}</a></td>
                        <td>{{ $surveyItem->courseRegister?->course?->teacher->user->full_name }}</td>
                        <td>{{ $surveyItem->star }}</td>
                        <td>{{ georgianToJalali($surveyItem->created_at, true) }}</td>
                        <td>
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu font-14">
                                @if (Auth::user()->hasPermissionTo(PermissionTitle::CANCEL_COURSE_REGISTER))
                                <span class="dropdown-item cursor-pointer" data-bs-toggle="modal" data-bs-target="#cancelCourseRegisterModal{{ $surveyItem->id }}"
                                    data-course-register-id="{{ $surveyItem->id }}">
                                    <i class="bx bx-show-alt me-1  "></i>مشاهده نظر
                                </span>
                                @endif
                                @if ($surveyItem->user->id !== Auth::user()->id and isAdminNumber())
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{ route('auth.login-by-user-id', [$surveyItem->user->id]) }}"><i class="bx bx-log-in me-1"></i>
                                    ورود با دسترسی کاربر</a>
                                @endif
                            </div>
                            <!-- Modal for Cancel Course Register -->
                            <div class="modal fade" id="cancelCourseRegisterModal{{ $surveyItem->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <h5> نظرسنجی : <span class="text-info">{{ $surveyItem->user->fullName }}</span></h5>
                                                </div>
                                                <hr>
                                                <div class="col-md-12 mb-2">
                                                    <h6>لطفا برای بهبود وضعیت آموزشی، پیشنهادات و انتقادات خود را بیان کنید : </h6>
                                                    <span>{!! $surveyItem->comment !!}</span>
                                                    <h6><span>امتیاز به ما</span> : {{ $surveyItem->star }}
                                                        @for ($i = 1; $i <= $surveyItem->star; $i++)
                                                            <i class="fa fa-star" style=" color: rgb(230, 219, 8)"></i>
                                                            @endfor
                                                    </h6>
                                                </div>
                                                <hr>
                                                @if ($surveyItem->q_1)
                                                <div class="col-md-12 mb-2">
                                                    <h6>
                                                        <span> 1- نظر شما راجع به دوره گذرانده شده در این آموزشگاه چیست؟ آیا دوره برای شما مفید بود؟ </span> :
                                                        <span class="badge bg-label-{{ $answers[$surveyItem->q_1]['color'] }}">{{ $answers[$surveyItem->q_1]['title'] }}</span>
                                                    </h6>
                                                    <h6> <span>توضیحات</span> : <span>{{ $surveyItem->q_1_comment ?? '--' }}</span> </h6>
                                                </div>
                                                <hr>
                                                @endif
                                                @if ($surveyItem->q_2)
                                                <div class="col-md-12 mb-2">
                                                    <h6>
                                                        <span>2- تدریس استاد و تسلط ایشان به موضوع درسی را در چه سطحی ارزیابی می کنید؟</span> :
                                                        <span class="badge bg-label-{{ $answers[$surveyItem->q_2]['color'] }}">{{ $answers[$surveyItem->q_2]['title'] }}</span>
                                                    </h6>

                                                    <h6> <span>توضیحات</span> : <span>{{ $surveyItem->q_2_comment ?? '--' }}</span> </h6>
                                                </div>
                                                <hr>
                                                @endif
                                                @if ($surveyItem->q_3)
                                                <div class="col-md-12 mb-2">
                                                    <h6>
                                                        <span>3- به نظر شما تجهیزات آموزشی کارگاه چطور بود؟</span> :
                                                        <span class="badge bg-label-{{ $answers[$surveyItem->q_3]['color'] }}">{{ $answers[$surveyItem->q_3]['title'] }}</span>
                                                    </h6>
                                                    <h6> <span>توضیحات</span> : <span>{{ $surveyItem->q_3_comment ?? '--' }}</span> </h6>
                                                </div>
                                                <hr>
                                                @endif
                                                @if ($surveyItem->q_4)
                                                <div class="col-md-12 mb-2">
                                                    <h6>
                                                        <span> 4- برخورد و رفتار کارکنان آموزشگاه و استاد دوره را چگونه ارزیابی می کنید؟
                                                            آیا شخصی هست که از برخوردش ناراضی باشید؟ لطفا نام ببرید</span> :
                                                        <span class="badge bg-label-{{ $answers[$surveyItem->q_4]['color'] }}">{{ $answers[$surveyItem->q_4]['title'] }}</span>
                                                    </h6>
                                                    <h6> <span>توضیحات</span> : <span>{{ $surveyItem->q_4_comment ?? '--' }}</span> </h6>
                                                </div>
                                                <hr>
                                                @endif
                                                @if ($surveyItem->yes_no_q_1)
                                                <div class="col-md-12 mb-2">
                                                    <h6>
                                                        <span> - آیا سرفصل های دوره به صورت کامل به شما آموزش داده شد؟</span> :
                                                        @if ($surveyItem->yes_no_q_1)
                                                        <span class="text-success"> بله </span>
                                                        @else
                                                        <span class="text-danger"> خیر </span>
                                                        @endif
                                                    </h6>
                                                </div>
                                                <hr>
                                                @endif
                                                @if ($surveyItem->yes_no_q_2)
                                                <div class="col-md-12 mb-2">
                                                    <h6>
                                                        <span> - آیا به میزان کافی، در کارگاه کار عملی انجام دادید؟</span> :
                                                        @if ($surveyItem->yes_no_q_2)
                                                        <span class="text-success"> بله </span>
                                                        @else
                                                        <span class="text-danger"> خیر </span>
                                                        @endif
                                                    </h6>
                                                </div>
                                                <hr>
                                                @endif
                                                @if ($surveyItem->yes_no_q_3)
                                                <div class="col-md-12 mb-2">
                                                    <h6>
                                                        <span> - در نهایت، آیا میزان رضایت شما از خروجی دوره به گونه ای بوده که
                                                            <br>
                                                            ما را در آینده به دوستان و آشنایان خود معرفی کنید؟</span> :
                                                        @if ($surveyItem->yes_no_q_3)
                                                        <span class="text-success"> بله </span>
                                                        @else
                                                        <span class="text-danger"> خیر </span>
                                                        @endif
                                                    </h6>
                                                </div>
                                                <hr>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($survey) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $survey->links() }}</span>
        </div>
    </div>
</div>
