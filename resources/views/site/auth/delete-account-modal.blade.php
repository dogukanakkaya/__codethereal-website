<div class="modal fade" id="delete-account-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="delete-account-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('site.auth.delete_account') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ce-f-group mb-3">
                        <label for="reason">{{ __('site.auth.explain_delete_account') }}</label>
                        <textarea name="reason" id="reason" rows="6"></textarea>
                    </div>
                    <p class="modal-info danger">{{ __('site.auth.delete_account_info') }}</p>
                    <div class="alert fade show d-none ce-alert" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="ce-btn me-0">{{ __('site.auth.delete_account') }} <i class="bi bi-check"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
