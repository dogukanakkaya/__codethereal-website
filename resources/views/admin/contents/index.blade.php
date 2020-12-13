@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contents/base.css') }}">
@endpush

@section('content')
    <x-breadcrumb :nav="$navigations"/>
    <div class="page-actions">
        {{ Form::sort(['onclick' => '__sort()']) }}
        {{ Form::refresh(['onclick' => '__refresh()']) }}
        {{ Form::addNew(['onclick' => '__create()']) }}
    </div>
    <div class="list-area p-4">
        @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])
        <x-datatable :url="route('contents.datatable')" :columns="$columns"/>
    </div>

    @include('admin.contents.form-modal')
@endsection

@push('scripts')
    <script>
        let updateId = 0
        const form = document.getElementById('content-form')
        const modal = '#content-form-modal'

        const __create = () => {
            if (updateId > 0) {
                form.reset()
                updateId = 0;
            }
            openModal(modal)
        }

        const __onResponse = response => {
            makeToast(response.data)
            if (response.data.status) {
                form.reset()
                closeModal(modal)
                __refresh()
            }
            toggleBtnLoading()
        }

        form.addEventListener('submit', e => {
            e.preventDefault()
            toggleBtnLoading()
            const formData = serialize(form, {hash: true, empty: true})
            if (updateId > 0) {
                const url = '{{ route('contents.update', ['id' => ':id']) }}'.replace(':id', updateId)
                request.put(url, formData).then(__onResponse)
            } else {
                request.post('{{ route('contents.create') }}', formData).then(__onResponse)
            }
        })

        const __update = id => {
            updateId = id
            clearPreviewFull1()

            const url = '{{ route('contents.update', ['id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(response => {
                    const {content, translations, files} = response.data

                    document.querySelector(`select[name="content[parent_id]"]`).value = content.parent_id
                    document.querySelector(`input[type=checkbox][name="content[searchable]"]`).checked = parseInt(content.searchable ?? 0) === 1

                    for (const [language, values] of Object.entries(translations)) {
                        document.querySelector(`input[name="${language}[title]"]`).value = values.title ?? ''
                        document.querySelector(`textarea[name="${language}[description]"]`).value = values.description ?? ''
                        tinymce.get(`${language}[full]`).setContent(values.full ?? '')
                        document.querySelector(`input[type=checkbox][name="${language}[active]"]`).checked = parseInt(values.active) === 1
                    }

                    for (const [fileId, filePath] of Object.entries(files)) {
                        createPreview1(fileId, storage(filePath))
                    }

                    openModal(modal)
                })
        }

        const __active = id => {

        }

        const __passive = id => {

        }
    </script>
@endpush
