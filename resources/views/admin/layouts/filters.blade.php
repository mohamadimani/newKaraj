@php
use App\Enums\OnlinePayment\StatusEnum;
@endphp
<div class="d-flex gap-2 col-md-12">
    <div class="col-md-2">
        <label for="search">{{ __('public.search') }}</label> :
        <input type="text" class="form-control " wire:model.live.debounce.500ms="search" placeholder="جستجو ...">
    </div>
    @if ($secretaries)
    <div class="col-md-2" wire:ignore>
        <label for="selectedSecretaryId">{{ __('clues.secretary') }}</label> :
        <select class="form-control select2 " id="selectedSecretaryId" wire:model.live.debounce.500ms="selectedSecretaryId" onchange="myFunction()">
            <option value="0">{{ __('public.all') }}</option>
            @foreach ($secretaries as $secretary)
            <option value="{{ $secretary->id }}">{{ $secretary->user->fullName }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-1">
        <label for="startDate">از</label> :
        <input data-jdp type="text" class="form-control " wire:model.live.debounce.500ms="startDate" placeholder="تاریخ">
        @include('admin.layouts.jdp', ['time' => false])
    </div>
    <div class="col-md-1">
        <label for="endDate">تا</label> :
        <input data-jdp type="text" class="form-control " wire:model.live.debounce.500ms="endDate" placeholder="تاریخ">
    </div>
    @endif
    @if (isset($teachersShow) and $teachersShow)
    <div class="col-md-2" wire:ignore>
        <label for="teacher">استاد</label> :
        <select class="form-control select2 " id="teacher" wire:model.live="teacherId" onchange="setTeacherId()">
            <option value="0">{{ __('public.all') }}</option>
            @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}">{{ $teacher->user->fullName }}</option>
            @endforeach
        </select>
    </div>
    <script>
        function setTeacherId() {
            const value = $('select#teacher').val();
            @this.setTeacherId(value)
        }
    </script>
    @endif
    @if (isset($courseShow) and $courseShow)
    <div class="col-md-2" wire:ignore>
        <label for="course">دوره</label> :
        <input type="text" class="form-control " wire:model.live="courseSearch" placeholder="جستجو ...">
    </div>
    @endif
    @if (isset($perofession) and $perofession == true)
    <div class="col-md-3" wire:ignore>
        <label class="form-label" for="profession_id">{{ __('courses.profession') }}</label>
        <select onchange="set_profession_id()" wire:model="profession_id" name="profession_id" id="profession_id" class="select2 form-select" placeholder="{{ __('courses.select') }}">
            <option value="">---</option>
            @foreach ($professions as $profession)
            <option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>{{ $profession->title }}</option>
            @endforeach
        </select>
    </div>
    @endif
    @if (isset($onlinePaymentStatus) and $onlinePaymentStatus == true)
    <div class="col-md-2" wire:ignore>
        <label for="onlinePaymentStatus">وضعیت :</label>
        <select wire:model.live="onlinePaymentStatus" id="onlinePaymentStatus" class="form-control">
            <option value="">همه</option>
            <option {{ $onlinePaymentStatus == StatusEnum::PENDING->value ? 'selected' : '' }} value="{{ StatusEnum::PENDING->value }}">در انتظار تایید</option>
            <option {{ $onlinePaymentStatus == StatusEnum::PAID->value ? 'selected' : '' }} value="{{ StatusEnum::PAID->value }}">تایید شده</option>
            <option {{ $onlinePaymentStatus == StatusEnum::CANCELED->value ? 'selected' : '' }} value="{{ StatusEnum::CANCELED->value }}">رد شده</option>
        </select>
    </div>
    @endif
    @if (isset($branch) and $branch == true)
    <div class="col-md-2" wire:ignore>
        <label for="branche_id">شعبه :</label>
        <select wire:model.live="branche_id" id="branche_id" class="form-control">
            <option value="">همه</option>
            @foreach ($branches as $branche)
            <option value="{{ $branche->id }}" {{ old('branche_id') == $branche->id ? 'selected' : '' }}>{{ $branche->name }}</option>
            @endforeach
        </select>
    </div>
    @endif
    @if (isset($onlinePayStatus) and $onlinePayStatus == true)
    <div class="col-md-2" wire:ignore>
        <label for="onlinePayStatus">وضعیت پرداخت :</label>
        <select wire:model.live="onlinePayStatus" id="onlinePayStatus" class="form-control">
            <option value="">همه</option>
            <option value="paid">تسویه شده</option>
            <option value="waiting_payment">در انتظار پرداخت</option>
            <option value="prepayment">پیش پرداخت</option>
        </select>
    </div>
    @endif
</div>
<script>
    function myFunction() {
        const value = $('select#selectedSecretaryId').val();
        @this.setSelectedSecretaryId(value)
    }

    function set_profession_id() {
        const value = $('select#profession_id').val();
        @this.setSelectedProfessionId(value)
    }
    set_profession_id()
    myFunction()
</script>
