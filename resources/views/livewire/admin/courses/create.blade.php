<div class="row">
    <div class="col-md-3 mb-3" wire:ignore>
        <label class="form-label" for="profession_id">{{ __('courses.profession') }}</label>
        <select onchange="myFunction()" wire:model="profession_id" name="profession_id" id="profession_id" class="select2 form-select" placeholder="{{ __('courses.select') }}">
            <option value="">---</option>
            @foreach ($professions as $profession)
                <option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>{{ $profession->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label" for="teacher_id">{{ __('courses.teacher') }}</label>
        <select wire:model="teacher_id" name="teacher_id" id="teacher_id" class="select2 form-select" placeholder="{{ __('courses.select') }}">
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->user->fullName }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label" for="branch_id">{{ __('courses.branch') }}</label>
        <select wire:model="branch_id" name="branch_id" id="branch_id" class="select2 form-select" placeholder="{{ __('courses.select') }}">
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label" for="class_room_id">{{ __('courses.class_room') }}</label>
        <select wire:model="class_room_id" name="class_room_id" id="class_room_id" class="select2 form-select" placeholder="{{ __('courses.select') }}">
            @foreach ($classRooms as $classRoom)
                <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>{{ $classRoom->branch->name . ' - ' . $classRoom->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <label class="form-label" for="title">{{ __('courses.title') }}</label>
        <input name="title" value="{{ old('title') }}" type="text" id="title" class="form-control text-start" placeholder="{{ __('courses.title_placeholder') }}">
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <label class="form-label" for="capacity">{{ __('courses.capacity') }}</label>
        <input name="capacity" wire:model="capacity" value="{{ old('capacity') }}" type="number" id="capacity" class="form-control text-start" placeholder="{{ __('courses.capacity_placeholder') }}">
    </div>

    <div class="col-md-2 col-sm-6 mb-3">
        <label class="form-label" for="price">{{ __('courses.price') }}</label>
        <input name="price" wire:model="price" value="{{ old('price') }}" type="text" id="price" class="form-control text-start" placeholder="{{ __('courses.price_placeholder') }}">
        <small class="text-muted" id="persian-text"></small>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <label class="form-label" for="duration_hours">{{ __('courses.duration_hours') }}</label>
        <input name="duration_hours" wire:model="duration_hours" value="{{ old('duration_hours') }}" type="number" id="duration_hours" class="form-control text-start"
            placeholder="{{ __('courses.duration_hours_placeholder') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label" for="course_type">{{ __('courses.course_type') }}</label>
        <select name="course_type" id="course_type" class="select2 form-select" {{ !$profession_id ? 'disabled' : '' }} onchange="myFunction()">
            @foreach (\App\Enums\Course\CourseTypeEnum::values() as $type)
                <option value="{{ $type }}" {{$course_type == $type ? 'selected' : '' }}>{{ __("courses.{$type}") }}</option>
            @endforeach
        </select>
    </div>
    <script>
        function myFunction() {
            const value = $('select#profession_id').val();
            const courseType = $('select#course_type').val();
            @this.setProfessionValue(value, courseType)
        }
        const priceInput = document.getElementById('price');
        const persianTextElement = document.getElementById('persian-text');

        priceInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');

            const numericValue = parseInt(this.value.replace(/,/g, ''));
            if (!isNaN(numericValue)) {
                persianTextElement.textContent = numberToPersianText(numericValue) + ' تومان';
            } else {
                persianTextElement.textContent = '';
            }
        });
        myFunction()

    </script>
</div>
