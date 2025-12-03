<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <h5 class="card-title">پرداخت های دوره های آنلاین</h5>
            <div class="row mb-4">

                @include('admin.layouts.alerts')
                @include('admin.layouts.filters', ['onlinePaymentStatus' => true , 'branch'=>true])
            </div>
            <div class="overflow-x-auto">
                <table class="table table-bordered table-responsive table-striped table-hover">
                    <thead class="bg-gray-50">
                        <tr class="text-center">
                            <th>ردیف</th>
                            <th>کارآموز</th>
                            <th>موبایل</th>
                            <th>شناسه سفارش</th>
                            <th>نام دوره</th>
                            <th>مبلغ سفارش</th>
                            <th>مبلغ پرداختی</th>
                            <th>وضعیت</th>
                            <th>ثبت کننده</th>
                            <th>تاریخ ثبت</th>
                            <th>تاریخ تغییر وضعیت</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($onlinePayments as $payment)
                        <tr class="text-center">
                            <td>{{ calcIterationNumber($onlinePayments , $loop) }}</td>
                            <td class="whitespace-nowrap"> {{ $payment->user->full_name }} </td>
                            <td> {{ $payment->user->mobile }}</td>
                            <td>
                                @if ($payment?->order)
                                <a class="text-primary d-block" href="{{ route('online-course-orders.show', $payment?->order?->id) }}">{{ $payment?->order?->id }}</a>
                                @else
                                <span class="label bg-label-danger">این سفارش حذف شده است</span>
                                @endif
                            </td>
                            <td> @if ($payment?->order)
                                @foreach ($payment->order->orderItems as $item)
                                {{ $item->onlineCourse->name }}<br>
                                @endforeach
                                @else
                                <span class="label bg-label-danger">این سفارش حذف شده است</span>
                                @endif
                            </td>
                            <td> {{ number_format($payment->amount) }} </td>
                            <td> {{ number_format($payment->paid_amount) }} </td>
                            <td>
                                @if ($payment->status == 'pending')
                                <span class="badge bg-label-warning">{{ __('در انتظار تایید') }}</span>
                                @elseif ($payment->status == 'paid')
                                <span class="badge bg-label-success">{{ __('تایید شده') }}</span>
                                @else
                                <span class="badge bg-label-danger">{{ __('رد شده') }}</span>
                                @endif
                            </td>
                            <td>{{ $payment->createdBy->full_name }}</td>
                            <td>{{ verta($payment->created_at)->format('Y/m/d - H:i:s') }}</td>
                            <td>{{ verta($payment->updated_at)->format('Y/m/d - H:i:s') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500"> پرداختی یافت نشد </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $onlinePayments->links() }}
            </div>
        </div>
    </div>
</div>
