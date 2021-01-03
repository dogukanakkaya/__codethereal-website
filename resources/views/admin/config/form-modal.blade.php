<div class="modal fade" id="config-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {{ Form::open(['id' => 'config-form']) }}
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            {{ Form::textarea('content', '', ['class' => 'form-control', 'required' => true, 'rows' => 25, 'style' => 'font-size: 1rem!important;']) }}
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
