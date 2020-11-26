<div class="modal fade" id="menuItemFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{ Form::open(['id' => 'menu-item-form']) }}
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
                                            {{ Form::label("$language->code[title]", __('menus.item_title'), ['class' => 'required']) }}
                                            {{ Form::text("$language->code[title]", '', ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::label("$language->code[url]", __('menus.item_url')) }}
                                            {{ Form::text("$language->code[url]", '', ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::label("$language->code[icon]", __('menus.item_icon')) }}
                                            {{ Form::text("$language->code[icon]", '', ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                {{ Form::hidden("$language->code[active]", 0) }}
                                                {{ Form::checkbox("$language->code[active]", 1, true, ['class' => 'custom-control-input', 'id' => "$language->code[active]"]) }}
                                                {{ Form::label("$language->code[active]", __('menus.item_active'), ['class' => 'custom-control-label']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="row pt-3 border-top">
                            <div class="col-12">
                                <div class="form-group">
                                    {{ Form::label("item[parent_id]", __('menus.item_parent')) }}
                                    {{ Form::select("item[parent_id]", [0 => __('global.no')] + $parents, 0, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('global.close') }} <i
                        class="fas fa-times"></i></button>
                <button type="submit" class="btn btn-primary">
                    <span class="btn-enabled">{{ __('global.save') }} <i class="fas fa-save"></i></span>
                    <span class="btn-disabled d-none">{{ __('global.loading') }} <i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
