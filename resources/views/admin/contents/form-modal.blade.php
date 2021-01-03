<div class="modal fade draggable" id="content-form-modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        {{ Form::open(['id' => 'content-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="nav nav-pills mb-3 border-active-tab" role="tablist">
                            <a class="nav-link active" data-bs-toggle="pill" href="#general" role="tab"
                               aria-selected="true"><i class="material-icons-outlined">layers</i> {{ __('contents.general') }}</a>
                            <a class="nav-link" data-bs-toggle="pill" href="#files" role="tab"
                               aria-selected="false"><i class="material-icons-outlined">insert_drive_file</i> {{ __('contents.files') }}</a>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="nav nav-pills mb-3 language-tab" style="top: 25px;position: absolute;right: 15px;" role="tablist">
                                    @foreach($languages as $language)
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill"
                                           href="#general-{{ $language->code }}" role="tab"
                                           aria-selected="true">{{ strtoupper($language->code) }}</a>
                                    @endforeach
                                </div>
                                <div class="tab-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                {{ Form::label("content[parent_id]", __('contents.parent')) }}
                                                {{ Form::select("content[parent_id]", [0 => __('global.none')] + $parents, 0, ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    {{ Form::checkbox("content[searchable]", 1, true, ['class' => 'form-check-input', 'id' => "content[searchable]"]) }}
                                                    {{ Form::label("content[searchable]", __('contents.searchable'), ['class' => 'form-check-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-top mb-3"></div>
                                    @foreach($languages as $key => $language)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                             id="general-{{ $language->code }}" role="tabpanel">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        {{ Form::label("$language->code[title]", __('contents.title'), ['class' => 'required']) }}
                                                        {{ Form::text("$language->code[title]", '', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        {{ Form::label("$language->code[description]", __('contents.description')) }}
                                                        {{ Form::textarea("$language->code[description]", '', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        {{ Form::label("$language->code[full]", __('contents.full')) }}
                                                        <x-rich-editor :name="$language->code . '[full]'"/>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3 form-check">
                                                        {{ Form::checkbox("$language->code[active]", 1, true, ['class' => 'form-check-input', 'id' => "$language->code[active]"]) }}
                                                        {{ Form::label("$language->code[active]", __('contents.active'), ['class' => 'form-check-label']) }}
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
                                            <div class="mb-3">
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
                {{ Form::closeBtn(['data-bs-dismiss' => 'modal']) }}
                {{ Form::save() }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
