<div class="modal" id="sent-sms-log-modal" tabindex="-1" aria-hidden="true">
    <style>
        .description_table th,
        .description_table td {
            padding: 1px !important;
            font-size: 12px !important;
        }
    </style>
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-1 ">
            <div class="modal-body">
                <div class="text-center mb-3 mt-0 mt-md-n2">
                    <h4 class="secondary-font">تاریخچه پیگیری</h4>
                </div>
                <div class="row g-3 pt-3">
                    @if ($userModel)
                        <div class="col-sm-12 mb-1">
                            <table class="table table-bordered table-striped table-hover text-center description_table">
                                <thead>
                                    <tr>
                                        <th>عنوان</th>
                                        <th>توضیحات</th>
                                        <th>تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($userModel->followUps as $followUp)
                                        <tr>
                                            <td>{{ $followUp->title }}</td>
                                            <td>{{ $followUp->description }}</td>
                                            <td>{{ georgianToJalali($followUp->created_at, true) }}</td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    @endif
                </div>
                <div class="text-center mb-3 mt-0 mt-md-n2">
                    <h4 class="secondary-font">تاریخچه پیامک</h4>
                </div>
                <div class="row g-3 pt-3">
                    @if ($userModel)
                        <div class="col-sm-12 mb-1">
                            <table class="table table-bordered table-striped table-hover text-center description_table">
                                <thead>
                                    <tr>
                                        <th>متن پیام</th>
                                        <th>ارسال کننده</th>
                                        <th>تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($userModel?->sendSmsLogs as $sendSmsLog)
                                        <tr>
                                            <td>{{ $sendSmsLog->message }}</td>
                                            <td class="bg-label-warning">{{ $sendSmsLog?->createdBy?->full_name }}</td>
                                            <td>{{ georgianToJalali($sendSmsLog->created_at, true) }}</td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
