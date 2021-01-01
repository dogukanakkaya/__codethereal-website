<div class="modal fade draggable" id="menu-group-form-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'menu-group-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            {{ Form::label('title', __('menus.group.title'), ['class' => 'required']) }}
                            {{ Form::text('title', '', ['class' => 'form-control', 'required' => true]) }}
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
