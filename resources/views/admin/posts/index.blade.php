@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ce/select2.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <x-breadcrumb :nav="$navigations"/>
        <div class="page-actions">
            @can('delete_posts')
                {{ Form::delete(['onclick' => '__deleteChecked()', 'class' => 'd-none delete-checked']) }}
            @endcan
            @can('sort_posts')
            {{ Form::sort(['onclick' => '__sort()']) }}
            @endcan
            {{ Form::refresh(['onclick' => '__refresh()']) }}
            @can('create_posts')
            {{ Form::addNew(['onclick' => '__create()']) }}
            @endcan
        </div>
    </div>

    <div class="list-area p-4">
        @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])
        <x-datatable :url="route('posts.datatable')" :columns="$columns"/>
    </div>

    @include('admin.posts.form-modal')
@endsection

@push('scripts')
    <script>
        let updateId = 0
        const form = document.getElementById('post-form')
        const modal = '#post-form-modal'

        const __create = () => {
            if (updateId > 0) {
                form.reset()
                $('select[name="post[parents][]"]').val([]).change()
                $('select[name="post[relations][]"]').val([]).change()
                updateId = 0;
                clearPreviewFull1()
            }
            openModal(modal)
        }

        const __onResponse = response => {
            makeToast(response.data)
            if (response.data.status) {
                form.reset()
                $('select[name="post[parents][]"]').val([]).change()
                $('select[name="post[relations][]"]').val([]).change()
                closeModal(modal)
                __refresh()
                clearPreviewFull1()
            }
            toggleBtnLoading()
        }

        form.addEventListener('submit', e => {
            e.preventDefault()
            toggleBtnLoading()
            const formData = serialize(form, {hash: true, empty: true})
            if (updateId > 0) {
                const url = '{{ route('posts.update', ['id' => ':id']) }}'.replace(':id', updateId)
                request.put(url, formData).then(__onResponse)
            } else {
                request.post('{{ route('posts.create') }}', formData).then(__onResponse)
            }
        })

        const __delete = id => {
            if (confirm('{{ __('messages.confirmation.delete.custom_message') }}')) {
                const url = '{{ route('posts.destroy', ['id' => ':id']) }}'.replace(':id', id)
                request.delete(url)
                    .then(res => {
                        res.data.addition = `<a href="javascript:void(0);" onclick="__undoDelete('${id}')">{{ __('buttons.undo') }}</a>`;
                        __onResponse(res)
                    })
            }
        }

        const __undoDelete = id => {
            const url = '{{ route('posts.restore', ['id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(__onResponse)
        }

        const __find = id => {
            updateId = id
            clearPreviewFull1()

            const url = '{{ route('posts.find', ['id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(response => {
                    const {post, translations, files, parents, relations} = response.data

                    document.querySelector(`input[type=checkbox][name="post[searchable]"]`).checked = parseInt(post.searchable ?? 0) === 1

                    for (const [language, values] of Object.entries(translations)) {
                        document.querySelector(`input[name="${language}[title]"]`).value = values.title ?? ''
                        document.querySelector(`input[name="${language}[meta_title]"]`).value = values.meta_title ?? ''
                        document.querySelector(`input[name="${language}[meta_description]"]`).value = values.meta_description ?? ''
                        document.querySelector(`input[name="${language}[meta_tags]"]`).value = values.meta_tags ?? ''
                        document.querySelector(`textarea[name="${language}[description]"]`).value = values.description ?? ''
                        tinymce.get(`${language}[full]`).setContent(values.full ?? '')
                        document.querySelector(`input[type=checkbox][name="${language}[active]"]`).checked = parseInt(values.active) === 1
                    }

                    for (const [fileId, filePath] of Object.entries(files)) {
                        createPreview1(fileId, storage(filePath))
                    }

                    $('select[name="post[parents][]"]').val(parents).change()
                    $('select[name="post[relations][]"]').val(relations).change()

                    openModal(modal)
                })
        }

        const __sort = () => window.location.href = '{{ route('posts.sort') }}'

        // TODO: to vanilla js
        $('.searchable-select').select2({
            width: '100%',
            allowClear: true
        })
    </script>
@endpush
