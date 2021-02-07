<div class="modal fade" id="register-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'register-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('auth.register') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ce-f-group mb-3">
                    {{ Form::label('name', __('users.fullname'), ['class' => 'required', 'minlength' => 5]) }}
                    {{ Form::text('name', '', ['required' => 'true']) }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('email', __('users.email'), ['class' => 'required']) }}
                    {{ Form::email('email', '', ['required' => 'true']) }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('phone', __('users.phone')) }}
                    {{ Form::text('phone', '') }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('password', __('users.password'), ['class' => 'required', 'minlength' => 8]) }}
                    {{ Form::password('password', ['required' => 'true']) }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('password_confirmation', __('users.confirm_password'), ['class' => 'required', 'minlength' => 8]) }}
                    {{ Form::password('password_confirmation', ['required' => 'true']) }}
                </div>
                <div class="mb-3 ce-check-f-group">
                    {{ Form::checkbox('subscribe', 1, false, ['id' => 'subscribe']) }}
                    {{ Form::label('subscribe', __('auth.subscribe_accept_text')) }}
                </div>
                <p class="modal-info secondary">
                    {!! __('site.auth.register_privacy_policy_info', [
                        'app_name' => config('app.name'),
                        'privacy' => '<span onclick="openModal(`#privacy-policy-modal`)">'.__('site.auth.privacy_policy').'</span>',
                        'terms' => '<span onclick="openModal(`#terms-of-service-modal`)">'.__('site.auth.terms_of_service').'</span>'
                    ]) !!}
                </p>
                <div class="alert fade show d-none ce-alert" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="ce-btn me-0">{{ __('auth.register') }} <i class="bi bi-person-plus"></i></button>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@include('site.auth.terms-of-service')
@include('site.auth.privacy-policy')
