<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row gy-4">
        <!-- User Sidebar -->
        <div class="col-md-5  order-1 order-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="  justify-content-between align-items-start">
                            <h5 class="">موجودی کیف پول</h5>
                            <div class="d-flex justify-content-center align-items-center">
                                <sup class="h5 pricing-currency mt-3 mt-sm-4 mb-0 me-1 text-primary">تومان</sup>
                                <h1 class="display-3 fw-normal mb-0 text-primary">{{ number_format(user()->wallet) }}</h1>
                            </div>
                        </div>
                        <h5 class="mt-5 mb-4 text-center">نحوه استفاده از کیف پول</h5>
                        <ul class="ps-3 g-2 mb-3 lh-1-85 p-0">
                            <li class="mb-2">خرید دوره های حضوری</li>
                            <li class="mb-2">خرید دوره های آنلاین</li>
                            <li class="mb-2">انتقال به حساب دیگران
                                <span class="d-block  text-info font-13"> <i class="fa fa-circle-info text-warning"></i> فقط به حساب کاربرانی که در هیچ دوره ای ثبت نام نکرده باشند</span>
                            </li>
                        </ul>
                        @if (!$user)
                        <div class="d-grid w-100 mt-5 cursor-pointer ">
                            <button class="btn btn-primary cursor-pointer" data-bs-target="#upgradePlanModal" data-bs-toggle="modal">
                                انتقال اعتبار
                            </button>
                        </div>
                        @endif
                        @if ($user)
                        <hr>
                        <h5 class="mb-0 text-center mt-3">
                            <span class="d-block"> انتقال مبلغ
                                <span class=" "> {{ number_format($amount) }} تومان </span>
                            </span>
                            <span class="d-block"> به حساب
                                <span class="">{{ $user->full_name  }}</span>
                            </span>
                            <span class="d-block"> انجام شود؟</span>
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mt-3 d-flex">
                                <div class="col-md-6  ">
                                    <button class="btn btn-label-danger w-100 " wire:click="cancel">لغو </button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-label-info w-100" wire:click="transfer()">تایید</button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="  justify-content-between align-items-start">
                            <h5 class="mt-5 mb-4 text-center">تاریخچه انتقال اعتبار</h5>
                            <div class=" ">
                                <style>
                                    table td,
                                    table th {
                                        padding: 1px 1px !important;
                                        font-size: 12px;
                                    }
                                </style>
                                <table class="table-bordered" id="dataTable" width="100%">
                                    <thead>
                                        <tr class="text-center ">
                                            <th>نام</th>
                                            <th>موبایل</th>
                                            <th>مبلغ <sub>(تومان)</sub></th>
                                            <th>تاریخ </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transfers as $index => $transfer)
                                        <tr class="text-center">
                                            <td>{{ $transfer->toUser->full_name }}</td>
                                            <td>{{ $transfer->toUser->mobile }}</td>
                                            <td><span class="badge bg-label-success">{{ number_format($transfer->amount) }}</span></td>
                                            <td>{{ verta($transfer->created_at)->format('Y/m/d H:i:s') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center"><span class="text-info"> انتقالی یافت نشد!</span></td>
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
