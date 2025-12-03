<div class="container-fluid flex-grow-1 container-p-y">
    @include('admin.layouts.alerts')
    @include('admin.layouts.jdp', ['time' => false])
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">پورسانت دوره های آنلاین</span>
        </div>
        <div class="card-body row">
            <div class='col-md-4'>
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="{{ __('teachers.search_placeholder') }}">
            </div>
            <div class='col-md-4'>
                <input data-jdp value="" type="text" wire:model.live.debounce.50ms="from_date" id="from_date" class="form-control" placeholder="از تاریخ">
            </div>
            <div class='col-md-4'>
                <input data-jdp value="" type="text" wire:model.live.debounce.50ms="to_date" id="to_date" class="form-control" placeholder="تا تاریخ">
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>{{ __('users.full_name') }}</th>
                        <th>{{ __('users.mobile') }}</th>
                        <th>تعداد فروش</th>
                        <th>پورسانت</th>
                        <th>{{ __('public.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($teachers as $index => $teacher)
                        <tr>
                            <td>{{ $teachers->firstItem() + $index}}</td>
                            <td>{{ $teacher->user->fullName }}</td>
                            <td>{{ $teacher->user->mobile }}</td>
                            <td>{{ $teacher->orderItems->whereBetween('pay_date', [$this->from_date_timestamp, $this->to_date_timestamp + 86400])->count() }}</td>
                            <td>{{ $this->calcTecherPercentage($teacher) }}</td>
                            <td>
                                <button wire:click="withdrawTeacherPercentConfirm({{ $teacher->id }})" class="btn btn-primary btn-sm">تسویه پورسانت</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($teachers) === 0)
                <div class="text-center py-5">
                    {{ __('messages.empty_table') }}
                </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $teachers->links() }}</span>
        </div>
    </div>
</div>
