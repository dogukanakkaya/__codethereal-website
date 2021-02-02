<div class="modal fade" id="contact-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'contact-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('site.contact.self_singular') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ce-f-group mb-3">
                    {{ Form::label('name', __('site.contact.name'), ['class' => 'required', 'minlength' => 5]) }}
                    {{ Form::text('name', '', ['required' => 'true']) }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('email', __('site.contact.email'), ['class' => 'required']) }}
                    {{ Form::email('email', '', ['required' => 'true']) }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('phone', __('site.contact.phone')) }}
                    {{ Form::text('phone', '') }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('subject', __('site.contact.subject'), ['class' => 'required', 'minlength' => 8]) }}
                    {{ Form::text('subject', '', ['required' => 'true']) }}
                </div>
                <div class="ce-f-group mb-3">
                    {{ Form::label('message', __('site.contact.message'), ['class' => 'required', 'minlength' => 8]) }}
                    {{ Form::textarea('message', '', ['required' => 'true']) }}
                </div>
                <div class="alert fade show d-none ce-alert" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="ce-btn me-0">{{ __('site.contact.send') }} <i class="bi bi-cursor"></i></button>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
