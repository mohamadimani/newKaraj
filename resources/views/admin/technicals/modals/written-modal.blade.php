<div class="modal" id="writtenExamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                @include('admin.layouts.alerts')
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4 mt-0 mt-md-n2">
                    <h3 class="secondary-font">تاریخ آزمون کتبی</h3>
                </div>
                @csrf
                <div class="row g-3 pt-3">
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="written_date">تاریخ آزمون کتبی</label>
                        <input data-jdp data-jdp-only-date type="text" wire:model="written_date" id="written_date" class="form-control text-start" placeholder="انتخاب" >
                        @include('admin.layouts.jdp')
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="written_description">توضیحات</label>
                        <textarea wire:model="written_description" rows="3" id="written_description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                        {{ __('public.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1" wire:click="storeWrittenExam" data-bs-dismiss="modal" aria-label="Close">{{ __('public.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
