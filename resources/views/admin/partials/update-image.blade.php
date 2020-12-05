<div class="modal fade" id="image-form-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'image-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="nav nav-pills mb-3 language-tab" role="tablist">
                    @foreach($languages as $language)
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="pill"
                           href="#form-{{ $language->code }}" role="tab"
                           aria-selected="true">{{ strtoupper($language->code) }}</a>
                    @endforeach
                </div>
                <div class="tab-content">
                    @foreach($languages as $key => $language)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                             id="form-{{ $language->code }}" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            {{ Form::hidden("$language->code[active]", 0) }}
                                            {{ Form::checkbox("$language->code[active]", 1, true, ['class' => 'custom-control-input', 'id' => "$language->code[active]"]) }}
                                            {{ Form::label("$language->code[active]", __('active'), ['class' => 'custom-control-label']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
