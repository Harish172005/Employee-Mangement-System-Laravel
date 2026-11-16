{{-- One shared modal. Any form with data-confirm="message" opens it (see delete-confirm.js) --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.please_confirm') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
            </div>
            <div class="modal-body" data-confirm-message>{{ __('messages.are_you_sure') }}</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-danger" data-confirm-ok>{{ __('messages.yes_continue') }}</button>
            </div>
        </div>
    </div>
</div>
