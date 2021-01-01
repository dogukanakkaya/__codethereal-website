<div class="modal fade draggable" id="permission-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'permission-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            {{ Form::label('title', __('permissions.title'), ['class' => 'required']) }}
                            {{ Form::text('title', '', ['class' => 'form-control', 'required' => true]) }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            {{ Form::label('name', __('permissions.name'), ['class' => 'required']) }}
                            {{ Form::text('name', '', ['class' => 'form-control', 'required' => true]) }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            {{ Form::label('group', __('permissions.group')) }}
                            {{ Form::text('group', '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{ Form::closeBtn(['data-bs-dismiss' => 'modal']) }}
                {{ Form::save() }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
