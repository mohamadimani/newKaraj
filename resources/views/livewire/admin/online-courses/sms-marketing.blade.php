<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h4 text-gray-800">افزودن پیام جدید</h1>
                <a href="{{ route('online-courses.index') }}" class="btn btn-primary">
                    <i class="fas fa-backward me-2"></i> لیست دوره های آنلاین
                </a>
            </div>
            @include('admin.layouts.alerts')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3" wire:ignore>
                        <label for="online_course_id">نام دوره</label>{{ requireSign() }} :
                        <select onchange="setOnlineCourseId()" id="online_course_id" class="form-control select2" multiple>
                            <option value="">انتخاب دوره</option>
                            @foreach ($onlineCourses as $course)
                            <option value="{{ $course->id }}" {{ old('online_course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="target_type">ارسال به </label>{{ requireSign() }} :
                        <select wire:model="target_type" class="form-control" id="target_type">
                            <option value="clue" {{ old('target_type') == 'clue' ? 'selected' : '' }}>سرنخ</option>
                            <option value="student" {{ old('target_type') == 'student' ? 'selected' : '' }}>کارآموز</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="after_time">زمان ارسال</label>{{ requireSign() }} : (روز)
                        <input type="number" wire:model="after_time" id="after_time" class="form-control" value="{{ old('after_time') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="message">متن پیام</label> {{ requireSign() }} :
                        <textarea wire:model="message" id="message" class="form-control">{{ old('message') }}</textarea>
                    </div>
                </div>
                <button wire:click="store()" class="btn btn-primary mt-3 w-100">ثبت</button>
            </div>
        </div>
        <hr>
        <div class="table-responsive text-nowrap">
            <div class="d-sm-flex align-items-center justify-content-between  m-3">
                <h1 class="h4 text-gray-800">لیست پیامک ها</h1>
            </div>
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>دوره</th>
                        <th>ارسال به</th>
                        <th>زمان ارسال (روز)</th>
                        <th>متن پیام</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($onlineMarketingSms as $key => $sms)
                    <tr>
                        <td>
                            @if ($editRowId and $editRowId == $sms->id)
                            <select wire:model="edit_online_course_id" class="form-control select2">
                                <option value="">انتخاب دوره</option>
                                @foreach ($onlineCourses as $course)
                                <option value="{{ $course->id }}" {{ old('edit_online_course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                @endforeach
                            </select>
                            @error('edit_online_course_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @else
                            {{ $sms->onlineCourse->name }}
                            @endif
                        </td>
                        <td>
                            @if ($editRowId and $editRowId == $sms->id)
                            <select wire:model="edit_target_type" class="form-control" id="edit_target_type">
                                <option value="clue" {{ old('edit_target_type') == 'clue' ? 'selected' : '' }}>سرنخ</option>
                                <option value="student" {{ old('edit_target_type') == 'student' ? 'selected' : '' }}>کارآموز</option>
                            </select>
                            @error('edit_target_type')
                            <br>
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @else
                            {{ $targetType[$sms->target_type] }}
                            @endif
                        </td>
                        <td>
                            @if ($editRowId and $editRowId == $sms->id)
                            <input type="number" wire:model='edit_after_time'>
                            @error('edit_after_time')
                            <br>
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @else
                            {{ $sms->after_time / 86400 }}
                            @endif
                        </td>
                        <td>
                            @if ($editRowId and $editRowId == $sms->id)
                            <textarea wire:model="edit_message" class="form-control">{{ old('edit_message') }}</textarea>
                            @error('edit_message')
                            <br>
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @else
                            {{ $sms->message }}
                            @endif
                        </td>
                        <td>
                            @if ($sms->is_active)
                            <button class="btn text-white btn-xs btn-success" wire:click='updateStatus({{ $sms->id }}, 0)'>{{ __('public.active') }}</button>
                            @else
                            <button class="btn text-white btn-xs btn-danger" wire:click='updateStatus({{ $sms->id }}, 1)'>{{ __('public.inactive') }}</button>
                            @endif
                        </td>
                        <td>
                            @if ($editRowId and $editRowId == $sms->id)
                            <span class="cursor-pointer btn btn-success btn-xs" wire:click='update({{ $sms->id }})'><i class="bx bx-check me-1"></i> ذخیره</span>
                            @else
                            <span class="cursor-pointer btn btn-info btn-xs" wire:click='edit({{ $sms->id }})'><i class="bx bx-edit-alt me-1"></i> ویرایش</span>
                            @endif
                            <span class="cursor-pointer btn btn-danger btn-xs" wire:click='deleteConfirm({{ $sms->id }})'><i class="bx bx-x-circle me-1"></i> حذف</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($onlineMarketingSms) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>

    </div>
    <script>
        function setOnlineCourseId() {
            const value = $('select#online_course_id').val();
            @this.setOnlineCourseId(value)
        }
    </script>
</div>
