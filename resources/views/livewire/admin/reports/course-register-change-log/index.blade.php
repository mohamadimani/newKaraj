<div class="container-fluid flex-grow-1 container-p-y">
    @php
    use App\Models\Course;
    use App\Enums\CourseRegister\StatusEnum;
    @endphp
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">گزارش تغییرات ثبت نام دوره</span>
        </div>
        <div class="card-body row">
            <div class='col-md-4'>
                <input type="text" wire:model.live="search" class="form-control" placeholder="جستجو">
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>نوع تغییر</th>
                        <th>برای</th>
                        <th>مقدار قبلی</th>
                        <th>مقدار جدید</th>
                        <th>توسط</th>
                        <th>توضیحات</th>
                        <th>تاریخ</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($courseRegisterLogs))
                    @foreach($courseRegisterLogs as $log)
                    <tr class="text-center">
                        @if ($log->field_name == 'course_id')
                        <td> تغییر دوره </td>
                        <td> {{ $log->getStudentFullName() }} </td>
                        <td class="text-wrap">{{ Course::find($log->previous_value)?->title}}</td>
                        <td class="text-wrap">{{ Course::find($log->new_value)?->title}}</td>
                        @endif

                        @if ($log->field_name == 'amount')
                        <td> تغییر مبلغ </td>
                        <td class="text-wrap"> <span class="bg-label-info">{{$log->getStudentFullName() }}</span> - {{ $log->courseRegister->course->title}}</td>
                        <td> {{ number_format($log->previous_value ?? $log->courseRegister->course->price) }} </td>
                        <td> {{ number_format($log->new_value )}} </td>
                        @endif

                        @if ($log->field_name == 'status')
                        <td> تغییر وضعیت </td>
                        <td>{{ $log->getStudentFullName() . ' - ' . $log->courseRegister->course->title}}</td>
                        <td> {{ $log->previous_value == StatusEnum::REGISTERED ?'ثبت نام' : ' --- ' }} </td>
                        <td> {{ $log->new_value == 'cancelled' ? 'انصراف' : ( $log->new_value == 'reserved' ? 'رزرو' : '---') }} </td>
                        @endif

                        <td><span class="bg-label-warning">{{ $log->user->fullName }}</span></td>
                        <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $log->description }}">
                            {{ substr($log->description, 0, 21) . (strlen($log->description) > 20 ? '...' : '') }}
                        </td>
                        <td>{{ verta($log->created_at)->format('Y/m/d H:i') }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            @if (count($courseRegisterLogs) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $courseRegisterLogs->links() }}</span>
        </div>
    </div>
</div>
