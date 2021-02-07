<div class="modal fade" id="terms-of-service-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('site.auth.terms_of_service') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="register-condition-content customize-content">
                    {!! $registerSettings['terms_of_service'] ?? '' !!}
                </div>
            </div>
        </div>
    </div>
</div>
