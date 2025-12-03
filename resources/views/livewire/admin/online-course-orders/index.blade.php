<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 text-gray-800">سفارش های دوره های آنلاین</h1>
            </div>
            <div class="d-flex justify-content-end mb-3">
                @include('admin.layouts.filters', ['onlinePayStatus' => true])
            </div>

            @include('admin.layouts.alerts')
            <div class="table-responsive">
                <style>
                    table td,
                    table th {
                        padding: 6px 4px !important;
                    }
                </style>
                <table class="table-bordered" id="dataTable" width="100%">
                    <thead>
                        <tr class="text-center font-13 ">
                            <th>#</th>
                            <th>کارآموز</th>
                            <th>موبایل</th>
                            <th> سفارش</th>
                            <th> ثبت کننده</th>
                            <th>مبلغ<sub>(تومان)</sub></th>
                            {{-- <th>تخفیف</th> --}}
                            <th>جمع</th>
                            <th>پرداختی</th>
                            <th>باقی مانده</th>
                            <th>وضعیت پرداخت</th>
                            <th>تاریخ ثبت</th>
                            {{-- <th>تاریخ تسویه</th> --}}
                            <th>توضیحات</th>
                            <th>گزینه ها</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $index => $order)
                            @php
                                $paidAmountSum = $order->onlinePayments()->where('pay_confirm', true)->sum('paid_amount');
                                $remainingAmount = $order->final_amount - $paidAmountSum;
                            @endphp
                            @php
                                if (($remainingAmount > 0 or $paidAmountSum == 0) and $onlinePayStatus == 'paid') {
                                    continue;
                                }
                                if ($paidAmountSum !== 0 and $onlinePayStatus == 'waiting_payment') {
                                    continue;
                                }
                                if (($remainingAmount <= 0 or $paidAmountSum == 0) and $onlinePayStatus == 'prepayment') {
                                    continue;
                                }
                            @endphp
                            <tr class="text-center font-13">
                                <td>{{ $orders->firstItem() + $index }}</td>
                                @if($order?->user?->student)
                                    <td><a href="{{ route('students.edit',[$order?->user?->student?->id]) }}">{{ $order->user->full_name }}</a></td>
                                @else
                                    <td>
                                        <a wire:click="makeStudentAccount({{ $order->user_id }})" class="dropdown-item">
                                            <i class="tf-icons text-primary fa-solid fa-edit me-2"></i>{{ $order->user->full_name }}
                                        </a>
                                    </td>
                                @endif
                                <td>{{ $order->user->mobile }}</td>
                                <td><a class="text-primary d-block" href="{{ route('online-course-orders.show', $order) }}">
                                        @foreach ($order->orderItems as $item)
                                            {{ $item->onlineCourse->name }}<br>
                                        @endforeach
                                    </a>
                                </td>
                                <td>{{ $order->createdBy->full_name }}</td>
                                {{-- <td>{{ number_format($order->total_amount) }}</td> --}}
                                <td>
                                    @foreach ($order->orderItems as $item)
                                        {{ number_format($item->final_amount) ?? '---' }}<br>
                                    @endforeach
                                </td>
                                {{-- <td>{{ number_format($order->discount_amount) }}</td> --}}
                                <td>{{ number_format($order->final_amount) }}</td>
                                <td>{{ number_format($paidAmountSum) }}</td>
                                <td><span class="text-{{ $remainingAmount > 0 ? 'danger' : 'success' }}">{{ number_format($remainingAmount) }}</span></td>
                                <td>
                                    @if ($remainingAmount <= 0)
                                        <span class="badge badge bg-label-success paid">تسویه شده</span>
                                    @elseif ($paidAmountSum == 0)
                                        <span class="badge badge bg-label-warning waiting_payment">در انتظار پرداخت</span>
                                    @elseif ($remainingAmount > 0)
                                        <span class="badge badge bg-label-info prepayment">پیش پرداخت</span>
                                    @endif
                                </td>
                                <td><span class="font-12">{{ verta($order->created_at)->format('Y/m/d H:i:s') }}</span></td>
                                {{-- <td><span class="font-12">{!! $order->pay_date ? verta(date('Y/m/d H:i', $order->pay_date))->format('Y/m/d H:i:s') : '<span class="badge bg-label-danger">در انتظار تایید مالی</span>' !!}</span></td> --}}
                                <td>
                                    @foreach ($order->orderItems as $item)
                                        {{ $item->change_amount_description ?? '---' }}<br>
                                    @endforeach
                                </td>

                                <td> <a href="{{ route('online-course-orders.show', $order) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a> </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center"><span class="text-info">سفارشی یافت نشد!</span></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <span class="d-block mt-3">{{ $orders->links() }}</span>
        </div>
    </div>
</div>
