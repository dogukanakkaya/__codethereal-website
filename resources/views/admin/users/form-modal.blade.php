<div class="modal fade" id="user-form-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'user-form']) }}
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
                            {{ Form::label('name', __('users.fullname'), ['class' => 'required']) }}
                            {{ Form::text('name', '', ['class' => 'form-control', 'required' => true]) }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('email', __('users.email'), ['class' => 'required']) }}
                            {{ Form::email('email', '', ['class' => 'form-control', 'required' => true]) }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('position', __('users.position')) }}
                            {{ Form::text('position', '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('about', __('users.about')) }}
                            {{ Form::textarea('about', '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label(__('users.photo')) }}
                            <x-dropzones.single folder="user-profiles" input-name="image"/>
                        </div>
                    </div>
                    <div class="col-12">
                        {{ Form::label(__('users.roles')) }}
                        <div id="user-permissions"></div>
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
