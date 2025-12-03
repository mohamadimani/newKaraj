<div class="modal" id="technical_description" tabindex="-1" aria-hidden="true">
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
                    <h4 class="secondary-font">تاریخچه توضیحات</h4>
                </div>
                <div class="row g-3 pt-3">
                    @if($technicalModel)
                    <div class="col-sm-12 mb-1">
                        <table class="table table-bordered table-striped table-hover text-center description_table">
                            <thead>
                                <tr>
                                    <th>توضیحات</th>
                                    <th>ثبت کننده</th>
                                    <th>تاریخ</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($technicalModel?->technicalDescriptions as $technicalDescription)
                                <tr>
                                    <td>{{ $technicalDescription->description }}</td>
                                    <td class="bg-label-warning">{{ $technicalDescription->createdBy->full_name }}</td>
                                    <td>{{ $technicalDescription->created_at }}</td>
                                </tr>
                                @endforeach
                        </table>
                    </div>
                    @endif
                </div>
                <div class="row g-3 pt-3">
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="technical_description">توضیحات : </label>
                        <textarea wire:model="technical_description" rows="3" id="technical_description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                        {{ __('public.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1" wire:click="storeTechnicalDescription" data-bs-dismiss="modal" aria-label="Close">{{
                        __('public.submit') }}</button>
                </div>
                <div class="row g-3 pt-3">
                    @if($technicalModel)
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
                                @foreach ($technicalModel->user?->sendSmsLogs as $sendSmsLog)
                                <tr>
                                    <td>{{ $sendSmsLog->message }}</td>
                                    <td class="bg-label-warning">{{ $sendSmsLog?->createdBy?->full_name }}</td>
                                    <td>{{ georgianToJalali($sendSmsLog->created_at,true) }}</td>
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