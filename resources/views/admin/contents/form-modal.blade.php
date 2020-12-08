<div class="modal fade" id="content-form-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        {{ Form::open(['id' => 'content-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="nav nav-pills mb-3 border-active-tab" role="tablist">
                            <a class="nav-link active" data-toggle="pill" href="#general" role="tab"
                               aria-selected="true">{{ __('contents.general') }}</a>
                            <a class="nav-link" data-toggle="pill" href="#files" role="tab"
                               aria-selected="false">{{ __('contents.files') }}</a>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="nav nav-pills mb-3 language-tab" role="tablist">
                                    @foreach($languages as $language)
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="pill"
                                           href="#general-{{ $language->code }}" role="tab"
                                           aria-selected="true">{{ strtoupper($language->code) }}</a>
                                    @endforeach
                                </div>
                                <div class="tab-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                {{ Form::label("content[parent_id]", __('contents.parent_content')) }}
                                                {{ Form::select("content[parent_id]", [0 => __('global.no')] + $parents, 0, ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-top mb-3"></div>

                                    @foreach($languages as $key => $language)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                             id="general-{{ $language->code }}" role="tabpanel">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        {{ Form::label("$language->code[title]", __('contents.title'), ['class' => 'required']) }}
                                                        {{ Form::text("$language->code[title]", '', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        {{ Form::label("$language->code[description]", __('contents.description')) }}
                                                        {{ Form::textarea("$language->code[description]", '', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        {{ Form::label("$language->code[full]", __('contents.full')) }}
                                                        <x-rich-editor :name="$language->code . '[full]'"/>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            {{ Form::checkbox("$language->code[active]", 1, true, ['class' => 'custom-control-input', 'id' => "$language->code[active]"]) }}
                                                            {{ Form::hidden("$language->code[active]", 0) }}
                                                            {{ Form::label("$language->code[active]", __('contents.active'), ['class' => 'custom-control-label']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            {{ Form::checkbox("$language->code[searchable]", 1, true, ['class' => 'custom-control-input', 'id' => "$language->code[searchable]"]) }}
                                                            {{ Form::hidden("$language->code[searchable]", 0) }}
                                                            {{ Form::label("$language->code[searchable]", __('contents.searchable'), ['class' => 'custom-control-label']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="files" role="tabpanel">
                                <div class="tab-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <x-dropzone index="1" input-name="content[files]" folder="contents"
                                                            sortable="true"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
