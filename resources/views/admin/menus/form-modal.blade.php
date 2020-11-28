<div class="modal fade" id="menu-group-form-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'menu-group-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('title', __('menus.group_title'), ['class' => 'required']) }}
                            {{ Form::text('title', '', ['class' => 'form-control', 'required' => true]) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{ Form::closeBtn(['data-dismiss' => 'modal']) }}
                {{ Form::save() }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
