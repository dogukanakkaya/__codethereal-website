<div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'login-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('auth.login') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ce-f-group mb-3">
                    {{ Form::label('email', __('users.email'), ['class' => 'required']) }}
                    {{ Form::email('email', '', ['required' => 'true']) }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('password', __('users.password'), ['class' => 'required']) }}
                    {{ Form::password('password', ['required' => 'true']) }}
                </div>
                <div class="mb-3 ce-check-f-group">
                    {{ Form::checkbox('remember_me', 1, false, ['id' => 'remember_me']) }}
                    {{ Form::label('remember_me', __('auth.remember_me')) }}
                </div>
                <div class="alert fade show d-none ce-alert" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="ce-btn me-0">{{ __('auth.login') }} <i class="bi bi-save"></i></button>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
