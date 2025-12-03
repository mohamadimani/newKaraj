<div class="modal fade" id="addFollowUpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4 mt-0 mt-md-n2">
                    <h3 class="secondary-font">{{ __('follow_ups.add_follow_up') }}</h3>
                </div>
                <form id="addFollowUpForm" class="row g-3" action="{{ route('follow-ups.store') }}" method="POST" wire:ignore>
                    @csrf
                    <div class="row g-3 pt-3">
                        <div class="col-sm-6 mb-1">
                            <label class="form-label" for="title">{{ __('follow_ups.title') }}</label>
                            <select name="title" id="title" class="form-control text-start" required>
                                <option value="">---</option>
                                <option value="{{ __('follow_ups.titles.register_course') }}">{{ __('follow_ups.titles.register_course') }}</option>
                                <option value="{{ __('follow_ups.titles.receive_payment') }}">{{ __('follow_ups.titles.receive_payment') }}</option>
                                <option value="{{ __('follow_ups.titles.reject_payment') }}">{{ __('follow_ups.titles.reject_payment') }}</option>
                            </select>
                        </div>
                        <!-- <div class="col-sm-6 mb-1">
                            <label class="form-label" for="remember_time">{{ __('follow_ups.remember_time') }}</label>
                            <input data-jdp name="remember_time" type="text" id="remember_time" class="form-control text-start" placeholder="انتخاب">
                        </div> -->
                        <div class="col-sm-12 mb-1">
                            <label class="form-label" for="description">{{ __('follow_ups.description') }}</label>
                            <textarea name="description" rows="3" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            {{ __('public.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('public.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.add-follow-up-button').on('click', function() {
            $('#user_id').val($(this).data('user-id'));
        });
    });
</script>
