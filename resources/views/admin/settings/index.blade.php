@extends('admin.layouts.base')

@section('content')
    <div class="d-flex justify-content-between align-items-center  mb-4">
        <x-breadcrumb :nav="$navigations"/>
    </div>
    <div class="settings">
        <div class="row">
            <div class="col-12 d-flex justify-content-between">
                <div class="nav nav-pills mb-3 border-active-tab" role="tablist">
                    <a class="nav-link active" data-bs-toggle="pill" href="#website" role="tab"
                       aria-selected="true">{{ __('settings.website') }}</a>
                    <a class="nav-link" data-bs-toggle="pill" href="#contact" role="tab"
                       aria-selected="false">{{ __('settings.contact') }}</a>
                    <a class="nav-link" data-bs-toggle="pill" href="#social" role="tab"
                       aria-selected="false">{{ __('settings.social') }}</a>
                </div>
            </div>
            <div class="col-12">
                <div class="list-area p-4">
                    {{ Form::open(['id' => 'setting-form']) }}
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="website" role="tabpanel">
                            <div class="nav nav-pills mb-3 language-tab" role="tablist">
                                @foreach($languages as $language)
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill" href="#website-{{ $language->code }}" role="tab" aria-selected="true">{{ strtoupper($language->code) }}</a>
                                @endforeach
                            </div>
                            <div class="tab-content">
                                @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])

                                @foreach($languages as $key => $language)
                                    @php
                                    $logo_white = "$language->code[logo_white]";
                                    $logo_dark = "$language->code[logo_dark]";

                                    $dzIndex = $language->code . "1";
                                    $dzIndex2 = $language->code . "2";
                                    @endphp
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="website-{{ $language->code }}" role="tabpanel">

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                {{ Form::label("$language->code[title]", __('settings.title')) }}
                                                {{ Form::text("$language->code[title]", $settings[$language->code]['title'] ?? '', ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                {{ Form::label("$language->code[description]", __('settings.description')) }}
                                                {{ Form::textarea("$language->code[description]", $settings[$language->code]['description'] ?? '', ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                {{ Form::label(__('settings.logo_white')) }}
                                                <x-dropzone :index="$dzIndex" :file-id="$settings[$language->code]['logo_white'] ?? 0" :input-name="$logo_white" max-files="1"/>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                {{ Form::label(__('settings.logo_dark')) }}
                                                <x-dropzone :index="$dzIndex2" :file-id="$settings[$language->code]['logo_dark'] ?? 0" :input-name="$logo_dark" max-files="1"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <div class="nav nav-pills mb-3 language-tab" role="tablist">
                                @foreach($languages as $language)
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill" href="#contact-{{ $language->code }}" role="tab" aria-selected="true">{{ strtoupper($language->code) }}</a>
                                @endforeach
                            </div>
                            <div class="tab-content">
                                @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])

                                @foreach($languages as $key => $language)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="contact-{{ $language->code }}" role="tabpanel">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    {{ Form::label("$language->code[phone]", __('settings.phone')) }}
                                                    {{ Form::text("$language->code[phone]", $settings[$language->code]['phone'] ?? '', ['class' => 'form-control']) }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    {{ Form::label("$language->code[email]", __('settings.email')) }}
                                                    {{ Form::text("$language->code[email]", $settings[$language->code]['email'] ?? '', ['class' => 'form-control']) }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    {{ Form::label("$language->code[address]", __('settings.address')) }}
                                                    {{ Form::textarea("$language->code[address]", $settings[$language->code]['address'] ?? '', ['class' => 'form-control']) }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    {{ Form::label("$language->code[map_code]", __('settings.map_code')) }}
                                                    {{ Form::text("$language->code[map_code]", $settings[$language->code]['map_code'] ?? '', ['class' => 'form-control']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="social" role="tabpanel">
                            <div class="nav nav-pills mb-3 language-tab" role="tablist">
                                @foreach($languages as $language)
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill" href="#social-{{ $language->code }}" role="tab" aria-selected="true">{{ strtoupper($language->code) }}</a>
                                @endforeach
                            </div>
                            <div class="tab-content">
                                @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])

                                @foreach($languages as $key => $language)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="social-{{ $language->code }}" role="tabpanel">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    {{ Form::label("$language->code[linkedin]", 'Linkedin') }}
                                                    {{ Form::text("$language->code[linkedin]", $settings[$language->code]['linkedin'] ?? '', ['class' => 'form-control']) }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    {{ Form::label("$language->code[github]", 'Github') }}
                                                    {{ Form::text("$language->code[github]", $settings[$language->code]['github'] ?? '', ['class' => 'form-control']) }}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        {{ Form::save() }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const form = document.getElementById('setting-form')
        form.addEventListener('submit', (e) => {
            e.preventDefault()
            toggleBtnLoading()
            const formData = serialize(form, { hash: true })
            request.put('{{ route('settings.update') }}', formData)
                .then(response => {
                    toggleBtnLoading()
                    makeToast(response.data)
                })
        })
    </script>
@endpush
