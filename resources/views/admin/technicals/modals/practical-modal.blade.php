<div class="modal   " id="practicalExamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                @include('admin.layouts.alerts')
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4 mt-0 mt-md-n2">
                    <h3 class="secondary-font">تاریخ آزمون عملی</h3>
                </div>
                @csrf
                <div class="row g-3 pt-3">
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="practical_date">تاریخ آزمون عملی</label>
                        <input data-jdp wire:model="practical_date" type="text" id="practical_date" class="form-control text-start" placeholder="انتخاب">
                        @include('admin.layouts.jdp')
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="practical_address">آدرس حرفه ای</label>
                        <select wire:model="practical_address" id="practical_address" class="form-control">
                            <option value="">انتخاب</option>
                            @foreach ($technicalAddress as $address)
                            <option value="{{ $address->id }}">{{ $address->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="practical_description">توضیحات</label>
                        <textarea wire:model="practical_description" rows="3" id="practical_description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                        {{ __('public.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1" wire:click="storePracticalExam" data-bs-dismiss="modal" aria-label="Close">{{ __('public.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>