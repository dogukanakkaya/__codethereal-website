<div class="modal fade" id="privacy-policy-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('site.auth.privacy_policy') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="register-condition-content customize-content">
                    {!! $registerSettings['privacy_policy'] ?? '' !!}
                </div>
            </div>
        </div>
    </div>
</div>
