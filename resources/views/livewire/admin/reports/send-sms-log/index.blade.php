<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card pb-3">
        <div class="align-items-center card-header d-flex justify-content-between">
            <span class="font-20 fw-bold heading-color">گزارش پیامک های ارسال شده</span>
        </div>
        <div class="table-responsive text-nowrap">
            <div class="card-header">
                <div class="col-md-3">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="جستجو">
                </div>
            </div>
            <div class="card-header">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>کاربر</th>
                            <th>موبایل</th>
                            <th>متن پیام</th>
                            <th>وضعیت</th>
                            <th>تاریخ ارسال</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($sendSmsLog as $sendSms)
                        <tr class="text-center">
                            <td>{{ $sendSmsLog->firstItem() + $loop->index }}</td>
                            <td>{{ $sendSms?->user?->full_name }}</td>
                            <td>{{ $sendSms?->mobile }}</td>
                            <td><span class="text-start word-wrap" style="white-space: pre-wrap;">{{ $sendSms?->message }}</span></td>
                            <td>
                                @if ($sendSms->is_sent)
                                <span class="badge bg-label-success">ارسال شده</span>
                                @else
                                <span class="badge bg-label-danger">ارسال نشده</span>
                                @endif
                            </td>
                            <td>{{ georgianToJalali($sendSms->created_at, true) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">هیچ پیامکی یافت نشد</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $sendSmsLog->links() }}
            </div>
        </div>
    </div>
</div>
