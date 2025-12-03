<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">گزارش تغییرات پرداخت</span>
        </div>
        <div class="card-body row">
            <div class='col-md-4'>
                {{-- search --}}
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>شناسه پرداخت</th>
                        <th>نوع تغییر</th>
                        <th>مقدار قبلی</th>
                        <th>مقدار جدید</th>
                        <th>توسط</th>
                        <th>تاریخ</th>
                        <th>توضیحات</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($paymentLogs as $paymentLog)
                    <tr class="text-center">
                        <td>{{ calcIterationNumber($paymentLogs, $loop) }}</td>
                        <td>{{ $paymentLog->payment->id }}</td>
                        <td>{{ $paymentLog->field_name_fa }}</td>
                        <td>{!! $paymentLog->previous_value !!}</td>
                        <td>{!! $paymentLog->new_value !!}</td>
                        <td>{{ $paymentLog->createdBy->fullName }}</td>
                        <td>{{ georgianToJalali($paymentLog->created_at, true) }}</td>
                        <td>{{ $paymentLog->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($paymentLogs) === 0)
            <div class="text-center py-5">
                {{ __('messages.empty_table') }}
            </div>
            @endif
        </div>
        <div class="p-3">
            <span class="d-block mt-3">{{ $paymentLogs->links() }}</span>
        </div>
    </div>
</div>