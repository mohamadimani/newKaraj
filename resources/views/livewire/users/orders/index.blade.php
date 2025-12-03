<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <!-- Tabs -->
            <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 py-3 px-4 border-bottom-0 rounded-top" data-bs-toggle="tab" href="#online-orders" role="tab" @if(request()->get('tab') ==
                        'online-orders') class="active" @endif>
                        <i class="bx bx-laptop fs-4"></i>
                        <span class="fw-semibold">سفارش های آنلاین من</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 py-3 px-4 border-bottom-0 rounded-top" data-bs-toggle="tab" href="#in-person-orders" role="tab" @if(request()->get('tab') ==
                        'in-person-orders') class="active" @endif>
                        <i class="bx bx-chalkboard fs-4"></i>
                        <span class="fw-semibold">سفارش های حضوری من</span>
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Online Orders Tab -->
                @include('admin.layouts.alerts')
                <div class="tab-pane  " id="online-orders" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold m-0">سفارش های آنلاین من</h4>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <input type="text" wire:model.live="onlineSearch" class="form-control" placeholder="جستجوی دوره">
                    </div>
                    <div class="table-responsive">
                        <style>
                            table td,
                            table th {
                                padding: 6px 4px !important;
                            }
                        </style>
                        <table class="table-bordered" id="dataTable" width="100%">
                            <thead>
                                <tr class="text-center ">
                                    <th>ردیف</th>
                                    <th>شناسه</th>
                                    <th>مبلغ(تومان)</th>
                                    <th>تخفیف (تومان)</th>
                                    <th>مبلغ نهایی(تومان)</th>
                                    <th>وضعیت پرداخت</th>
                                    <th>تاریخ ثبت</th>
                                    <th>گزینه ها</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($onlineOrders as $index => $order)
                                <tr class="text-center">
                                    <td>{{ $onlineOrders->firstItem() + $index }}</td>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ number_format($order->total_amount) }}</td>
                                    <td>{{ number_format($order->discount_amount) }}</td>
                                    <td>{{ number_format($order->final_amount) }}</td>
                                    <td>
                                        @if ($order->payment_status == 'pending')
                                        <span class="badge bg-warning">در انتظار پرداخت</span>
                                        @elseif ($order->payment_status == 'paid')
                                        <span class="badge bg-success">پرداخت شده</span>
                                        @endif
                                    </td>
                                    <td>{{ verta($order->created_at)->format('Y/m/d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                            مشاهده سفارش</a>
                                        @if ($order->payment_status == 'pending')
                                        <a href="{{ route('user.orders.pay', $order) }}" class="btn btn-sm btn-success">
                                            پرداخت</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center"><span class="text-info">سفارش آنلاینی یافت نشد!</span></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <span class="d-block mt-3">{{ $onlineOrders->links() }}</span>
                </div>

                <!-- In-Person Orders Tab -->
                <div class="tab-pane" id="in-person-orders" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold m-0">سفارش های حضوری من</h4>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <input type="text" wire:model.live="courseSearch" class="form-control" placeholder="جستجوی دوره">
                    </div>
                    <div class="table-responsive">
                        <table class="table-bordered" id="dataTable" width="100%">
                            <thead>
                                <tr class="text-center ">
                                    <th>ردیف</th>
                                    <th>شناسه</th>
                                    <th>مبلغ(تومان)</th>
                                    <th>وضعیت پرداخت</th>
                                    <th>تاریخ ثبت</th>
                                    <th>گزینه ها</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($courseOrders as $index => $courseOrder)
                                <tr class="text-center">
                                    <td>{{ $courseOrders->firstItem() + $index }}</td>
                                    <td>{{ $courseOrder->id }}</td>
                                    <td>{{ number_format($courseOrder->total_amount) }}</td>
                                    <td>
                                        @if ($courseOrder->payment_status == 'pending')
                                        <span class="badge bg-warning">در انتظار پرداخت</span>
                                        @elseif ($courseOrder->payment_status == 'paid')
                                        <span class="badge bg-success">پرداخت شده</span>
                                        @endif
                                    </td>
                                    <td>{{ verta($courseOrder->created_at)->format('Y/m/d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('user.course-orders.show', $courseOrder) }}" class="btn btn-sm btn-primary">
                                            مشاهده سفارش</a>
                                        @if ($courseOrder->payment_status == 'pending')
                                        <a href="{{ route('user.course-orders.pay', $courseOrder) }}" class="btn btn-sm btn-success">
                                            پرداخت</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center"><span class="text-info">سفارش حضوری یافت نشد!</span></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <span class="d-block mt-3">{{ $courseOrders->links() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>