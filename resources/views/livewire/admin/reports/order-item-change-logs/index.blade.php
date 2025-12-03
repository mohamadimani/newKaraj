<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">گزارش تغییرات سفارش</span>
        </div>
        <div class="card-body row">
            <div class='col-md-4'>
                <input type="text" wire:model.live="search" class="form-control" placeholder="جستجو">
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>دوره ها</th>
                        <th>نوع تغییر</th>
                        <th>مقدار قبلی</th>
                        <th>مقدار جدید</th>
                        <th>توسط</th>
                        <th>تاریخ</th>
                        <th>توضیحات</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($orderItemChangeLogs as $orderItem)
                    <tr class="text-center">
                        <td>{{ calcIterationNumber($orderItemChangeLogs, $loop) }}</td>
                        <td>{{ $orderItem->orderItem->onlineCourse->name}}</td>
                        <td>{{ $orderItem->field_name_fa }}</td>
                        <td>{!! number_format($orderItem->previous_value) !!}</td>
                        <td>{!! number_format($orderItem->new_value) !!}</td>
                        <td>{{ $orderItem->createdBy->fullName }}</td>
                        <td>{{ $orderItem->created_at }}</td>
                        <td>{{ $orderItem->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($orderItemChangeLogs) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $orderItemChangeLogs->links() }}</span>
        </div>
    </div>
</div>
