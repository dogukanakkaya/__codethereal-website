<div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('auth.login') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ce-f-group mb-3">
                    {{ Form::label('email', __('users.email')) }}
                    {{ Form::email('email', '', ['required' => 'true']) }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('password', __('users.password')) }}
                    {{ Form::password('password', ['required' => 'true']) }}
                </div>
                <div class="mb-3">
                    {{ Form::checkbox('remember', 'on') }} {{__('auth.remember_me')}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="ce-btn me-0">{{ __('auth.login') }} <i class="bi bi-save"></i></button>
            </div>
        </div>
    </div>
</div>
