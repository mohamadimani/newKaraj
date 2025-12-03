<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row gy-4">
        <!-- User Sidebar -->
        <div class="col-md-5  order-1 order-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="  justify-content-between align-items-start">
                            <h5 class="">کد معرفی شما</h5>
                            <div class="d-flex justify-content-center align-items-center">
                                @if (user()->reference_code ?? $referenceCode)
                                    <h1 class="display-3 fw-normal mb-0 text-primary">{{ user()->reference_code ?? $referenceCode }}</h1>
                                @else
                                    <span class="btn btn-info" wire:click='setReferenceCode'><i class="fa fa-refresh me-2"></i> ایجاد کد دعوت </span>
                                @endif
                            </div>
                        </div>
                        <h5 class="mt-5 mb-4 text-center">نحوه استفاده از کد دعوت</h5>
                        <ul class="ps-3 g-2 mb-3 lh-1-85 p-0">
                            {{-- <li class="mb-2">خرید دوره های حضوری با کد معرف شما</li> --}}
                            <li class="mb-2">خرید دوره های آنلاین با کد معرف شما</li>
                        </ul>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="  justify-content-between align-items-start">
                            <h5 class="mt-4 mb-4 text-center">تاریخچه دعوت</h5>
                            <div class=" ">
                                <style>
                                    table td,
                                    table th {
                                        padding: 1px 1px !important;
                                        font-size: 14px;
                                    }
                                </style>
                                <table class="table-bordered" id="dataTable" width="100%">
                                    <thead>
                                        <tr class="text-center ">
                                            <th>نام</th>
                                            <th>مبلغ <sub>(تومان)</sub></th>
                                            <th>تاریخ </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($referenceOrders as $index => $order)
                                            <tr class="text-center">
                                                <td>{{ $order->user->full_name }}</td>
                                                <td><span class="badge bg-label-success">{{ number_format($order->final_amount * 0.1) }}</span></td>
                                                <td>{{ verta($order->created_at)->format('Y/m/d H:i:s') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center"><span class="text-info"> دعوتی یافت نشد!</span></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  Modal -->
                <div class="modal fade" id="upgradePlanModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-simple modal-upgrade-plan">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-body" wire:ignore>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="text-center mb-4 mt-0 mt-md-n2">
                                    <h3 class="secondary-font"> انتقال اعتبار</h3>
                                    <p>شماره موبایل کاربری که میخواهید اعتبار انتقال بدهید را وارد کنید</p>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="choosePlan">موبایل : </label>
                                    <input type="number" class="form-control w-100" wire:model="mobile" required>
                                </div>
                                <div class="col-md-12 mt-3 mb-4">
                                    <label class="form-label" for="choosePlan">مقدار اعتبار : </label>
                                    <input type="number" class="form-control w-100" wire:model="amount" required>
                                </div>
                                <div class="col-md-12 d-flex align-items-end mt-2">
                                    <button wire:click="search()" class="btn btn-primary w-100" data-bs-dismiss="modal" aria-label="Close">جستجو</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/  Modal -->
            </div>
        </div>
    </div>
</div>
