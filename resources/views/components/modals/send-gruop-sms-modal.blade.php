<div class="modal fade" id="sendGroupSmsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mt-0 mt-md-n2">
                    <h3 class="secondary-font">{{ __('clues.send_multi_sms') }}</h3>
                </div>
                <div class="row g-3">
                    <div class="col-sm-12 mb-1">
                        <label class="form-label" for="sms_message">{{ __('clues.sms_message') }}</label>
                        <textarea wire:model="smsMessage" rows="5" id="sms_message" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                        {{ __('public.cancel') }}
                    </button>
                    <button wire:click="sendSms()" class="btn btn-primary me-sm-3 me-1">{{ __('public.send') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>