<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">گزارش کدهای ورود</span>
        </div>
        <div class="table-responsive text-nowrap">
            <div class="card-header">
                <div class="col-md-3">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="جستجو">
                </div>
            </div>
            <div class="card-header ">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>کاربر</th>
                            <th>موبایل</th>
                            <th>کد تایید</th>
                            <th>وضعیت</th>
                            <th>تاریخ ایجاد</th>
                            <th>تاریخ استفاده</th>
                            <th>تاریخ انقضا</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($verificationCodes as $verificationCode)
                        <tr class="text-center">
                            <td>{{ $verificationCodes->firstItem() + $loop->index }}</td>
                            <td>{{ $verificationCode?->user?->full_name }}</td>
                            <td>{{ $verificationCode?->mobile }}</td>
                            <td>{{ $verificationCode?->otp }}</td>
                            <td>
                                @if ($verificationCode->used_at)
                                <span class="badge bg-label-success">استفاده شده</span>
                                @elseif ($verificationCode->expires_at < now()) <span class="badge bg-label-danger">منقضی شده</span>
                                    @else
                                    <span class="badge bg-label-primary">فعال</span>
                                    @endif
                            </td>
                            <td>{{ georgianToJalali($verificationCode->created_at, true) }}</td>
                            <td>
                                @if ($verificationCode->used_at)
                                {{ georgianToJalali($verificationCode->used_at, true) }}
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ georgianToJalali($verificationCode->expires_at, true) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">هیچ کد تاییدی یافت نشد</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $verificationCodes->links() }}
            </div>
        </div>
    </div>
</div>
