<div class="modal fade" id="image-form-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="image-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('dropzone.edit_file') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        <div class="mb-3">
                                            {{ Form::label("$language->code[file_title]", __('title')) }}
                                            {{ Form::text("$language->code[file_title]", '', ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            {{ Form::label("$language->code[file_alt]", __('alt')) }}
                                            {{ Form::text("$language->code[file_alt]", '', ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-switch">
                                                {{ Form::hidden("$language->code[file_active]", 0) }}
                                                {{ Form::checkbox("$language->code[file_active]", 1, true, ['class' => 'custom-control-input', 'id' => "$language->code[file_active]"]) }}
                                                {{ Form::label("$language->code[file_active]", __('active'), ['class' => 'custom-control-label']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                            <div class="row pt-3 border-top">
                                <div class="col-12">
                                    <div class="mb-3">
                                        {{ Form::radio("file[type]", 1, true, ['id' => 'file-type-1']) }} {{ Form::label("file-type-1", __('normal')) }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        {{ Form::radio("file[type]", 2, false, ['id' => 'file-type-2']) }} {{ Form::label("file-type-2", __('onecikarilan')) }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        {{ Form::radio("file[type]", 3, false, ['id' => 'file-type-3']) }} {{ Form::label("file-type-3", __('genis')) }}
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{ Form::closeBtn(['data-bs-dismiss' => 'modal']) }}
                    {{ Form::save() }}
                </div>
            </div>
        </form>
    </div>
</div>
