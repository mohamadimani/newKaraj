<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    @php
    use App\Models\Course;
    use App\Enums\CourseRegister\StatusEnum;
    @endphp
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تاریخچه 100 تغییر آخر</h5>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered">
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
                                <td>{{ Course::find($log->previous_value)?->title}}</td>
                                <td>{{ Course::find($log->new_value)?->title}}</td>
                                @endif

                                @if ($log->field_name == 'amount')
                                <td> تغییر مبلغ </td>
                                <td>{{ $log->getStudentFullName() . ' - ' . $log->courseRegister->course->title}}</td>
                                <td> {{ number_format($log->previous_value ?? $log->courseRegister->course->price) }} </td>
                                <td> {{ number_format($log->new_value )}} </td>
                                @endif

                                @if ($log->field_name == 'status')
                                <td> تغییر وضعیت </td>
                                <td>{{ $log->getStudentFullName() . ' - ' . $log->courseRegister->course->title}}</td>
                                <td> {{ $log->previous_value == StatusEnum::REGISTERED ?'ثبت نام' : ' --- '  }} </td>
                                <td> {{ $log->new_value == 'cancelled' ? 'انصراف' : ( $log->new_value == 'reserved' ? 'رزرو'  : '---') }} </td>
                                @endif

                                <td class="text-warning">{{ $log->user->fullName }}</td>
                                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $log->description }}">
                                    {{ substr($log->description, 0, 21) . (strlen($log->description) > 20 ? '...' : '') }}
                                </td>
                                <td>{{ verta($log->created_at)->format('Y/m/d H:i') }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>