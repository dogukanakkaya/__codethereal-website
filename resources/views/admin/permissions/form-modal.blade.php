<div class="modal fade" id="permission-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'permission-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('title', __('permissions.title'), ['class' => 'required']) }}
                            {{ Form::text('title', '', ['class' => 'form-control', 'required' => true]) }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('name', __('permissions.name'), ['class' => 'required']) }}
                            {{ Form::text('name', '', ['class' => 'form-control', 'required' => true]) }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('group', __('permissions.group')) }}
                            {{ Form::text('group', '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('global.close') }} <i class="fas fa-times"></i></button>
                <button type="submit" class="btn btn-primary">
                    <span class="btn-enabled">{{ __('global.save') }} <i class="fas fa-save"></i></span>
                    <span class="btn-disabled d-none">{{ __('global.loading') }} <i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
