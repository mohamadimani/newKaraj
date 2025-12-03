<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">
            <h5 class="card-title">پرداخت های دوره های آنلاین</h5>
            <div class="row mb-4">

                @include('admin.layouts.alerts')
                @include('admin.layouts.filters', ['onlinePaymentStatus' => true])
            </div>
            <div class="overflow-x-auto">
                <table class="table table-bordered table-responsive table-striped table-hover">
                    <thead class="bg-gray-50">
                        <tr class="text-center">
                            <th>ردیف</th>
                            <th>کارآموز</th>
                            <th>موبایل</th>
                            <th>نام دوره</th>
                            <th>مبلغ سفارش</th>
                            <th>مبلغ پرداختی</th>
                            <th>وضعیت</th>
                            <th>ثبت کننده</th>
                            <th>تاریخ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($coursePayments as $payment)
                        <tr class="text-center">
                            <td>{{ calcIterationNumber($coursePayments, $loop)  }}</td>
                            <td class="whitespace-nowrap"> {{ $payment->user->full_name }} </td>
                            <td> {{ $payment->user->mobile }}</td>
                            {{-- <td>{{ $payment->course_order_id}}</td> --}}
                            <td>
                                @foreach ($payment->courseOrder->CourseOrderItems as $item)
                                @if($item->Course)
                                {{ $item?->Course?->title }}<br>
                                @else
                                <span class="label bg-label-danger">این دوره حذف شده است</span>
                                @endif
                                @endforeach
                            </td>
                            <td> {{ number_format($payment->amount) }} </td>
                            <td> {{ number_format($payment->paid_amount) }} </td>
                            <td>
                                @if ($payment->status == 'pending')
                                <span class="badge bg-label-warning">{{ __('در انتظار پرداخت') }}</span>
                                @elseif ($payment->status == 'paid')
                                <span class="badge bg-label-success">{{ __('پرداخت شده') }}</span>
                                @else
                                <span class="badge bg-label-danger">{{ __('ناموفق') }}</span>
                                @endif
                            </td>
                            <td>{{ $payment->createdBy->full_name }}</td>
                            <td>{{ verta($payment->created_at)->format('Y/m/d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500"> پرداختی یافت نشد </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $coursePayments->links() }}
            </div>
        </div>
    </div>
</div>
