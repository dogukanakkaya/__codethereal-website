<div class="modal fade draggable" id="menu-item-form-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'menu-item-form']) }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
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
                                            {{ Form::label("$language->code[title]", __('menus.item.title'), ['class' => 'required']) }}
                                            {{ Form::text("$language->code[title]", '', ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            {{ Form::label("$language->code[url]", __('menus.item.url')) }}
                                            {{ Form::text("$language->code[url]", '', ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            {{ Form::label("$language->code[icon]", __('menus.item.icon')) }}
                                            {{ Form::text("$language->code[icon]", '', ['class' => 'form-control']) }}
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
                        <div class="row pt-3 border-top">
                            <div class="col-12">
                                <div class="mb-3">
                                    {{ Form::label("item[parent_id]", __('menus.item.parent')) }}
                                    {{ Form::select("item[parent_id]", [0 => __('global.none')] + $parents, 0, ['class' => 'form-control']) }}
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
