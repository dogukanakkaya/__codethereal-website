<div class="modal fade draggable" id="post-form-modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        {{ Form::open(['id' => 'post-form']) }}
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="nav nav-pills mb-3 border-active-tab" role="tablist">
                            <a class="nav-link active" data-bs-toggle="pill" href="#general" role="tab"
                               aria-selected="true"><i class="material-icons-outlined">layers</i> {{ __('posts.general') }}</a>
                            <a class="nav-link" data-bs-toggle="pill" href="#seo" role="tab"
                               aria-selected="false"><i class="material-icons-outlined">share</i> {{ __('posts.seo.self_singular') }}</a>
                            <a class="nav-link" data-bs-toggle="pill" href="#files" role="tab"
                               aria-selected="false"><i class="material-icons-outlined">insert_drive_file</i> {{ __('posts.files') }}</a>
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
                                                {{ Form::label("post[parents][]", __('posts.parents')) }}
                                                {{ Form::select("post[parents][]", $posts, 0, ['class' => 'form-control searchable-select', 'multiple' => true]) }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    {{ Form::checkbox("post[searchable]", 1, true, ['class' => 'form-check-input', 'id' => "post[searchable]"]) }}
                                                    {{ Form::label("post[searchable]", __('posts.searchable'), ['class' => 'form-check-label']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                {{ Form::label("post[relations][]", __('posts.relations')) }}
                                                {{ Form::select("post[relations][]", $posts, 0, ['class' => 'form-control searchable-select', 'multiple' => true]) }}
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
                                                        {{ Form::label("$language->code[title]", __('posts.title'), ['class' => 'required']) }}
                                                        {{ Form::text("$language->code[title]", '', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        {{ Form::label("$language->code[description]", __('posts.description')) }}
                                                        {{ Form::textarea("$language->code[description]", '', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        {{ Form::label("$language->code[full]", __('posts.full')) }}
                                                        <x-rich-editor :name="$language->code . '[full]'"/>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3 form-check">
                                                        {{ Form::checkbox("$language->code[active]", 1, true, ['class' => 'form-check-input', 'id' => "$language->code[active]"]) }}
                                                        {{ Form::label("$language->code[active]", __('posts.active'), ['class' => 'form-check-label']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="seo" role="tabpanel">
                                <div class="nav nav-pills mb-3 language-tab" style="top: 25px;position: absolute;right: 15px;" role="tablist">
                                    @foreach($languages as $language)
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill"
                                           href="#seo-{{ $language->code }}" role="tab"
                                           aria-selected="true">{{ strtoupper($language->code) }}</a>
                                    @endforeach
                                </div>
                                <div class="tab-content">
                                    @foreach($languages as $key => $language)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                             id="seo-{{ $language->code }}" role="tabpanel">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        {{ Form::label("$language->code[meta_title]", __('posts.seo.title')) }}
                                                        {{ Form::text("$language->code[meta_title]", '', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        {{ Form::label("$language->code[meta_description]", __('posts.seo.description')) }}
                                                        {{ Form::text("$language->code[meta_description]", '', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        {{ Form::label("$language->code[meta_tags]", __('posts.seo.tags')) }}
                                                        {{ Form::text("$language->code[meta_tags]", '', ['class' => 'form-control']) }}
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
                                                <x-dropzone index="1" input-name="post[files]" folder="posts"
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
