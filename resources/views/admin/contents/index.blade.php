@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contents/base.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <x-breadcrumb :nav="$navigations"/>
        <div class="page-actions">
            @can('delete_contents')
                {{ Form::delete(['onclick' => '__deleteChecked()', 'class' => 'd-none delete-checked']) }}
            @endcan
            @can('sort_contents')
            {{ Form::sort(['onclick' => '__sort()']) }}
            @endcan
            {{ Form::refresh(['onclick' => '__refresh()']) }}
            @can('sort_contents')
            {{ Form::addNew(['onclick' => '__create()']) }}
            @endcan
        </div>
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
                clearPreviewFull1()
            }
            openModal(modal)
            changeModalTitle(modal, '{{ __('contents.add_new') }}')
        }

        const __onResponse = response => {
            makeToast(response.data)
            if (response.data.status) {
                form.reset()
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
                const url = '{{ route('contents.update', ['id' => ':id']) }}'.replace(':id', updateId)
                request.put(url, formData).then(__onResponse)
            } else {
                request.post('{{ route('contents.create') }}', formData).then(__onResponse)
            }
        })

        const __delete = id => {
            if (confirm('{{ __('messages.confirmation.delete.custom_message') }}')) {
                const url = '{{ route('contents.destroy', ['id' => ':id']) }}'.replace(':id', id)
                request.delete(url)
                    .then(res => {
                        res.data.addition = `<a href="javascript:void(0);" onclick="__undoDelete(${id})">{{ __('buttons.undo') }}</a>`;
                        __onResponse(res)
                    })
            }
        }

        const __undoDelete = id => {
            const url = '{{ route('contents.restore', ['id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(__onResponse)
        }

        const __find = id => {
            updateId = id
            clearPreviewFull1()

            const url = '{{ route('contents.find', ['id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(response => {
                    const {content, translations, files, parents} = response.data

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

                    // Convert parent ids to integer
                    const newParents = parents.map(parent => parseInt(parent))
                    for (const option of document.querySelectorAll(`select[name="content[parents][]"] option`)) {
                        const value = parseInt(option.value)

                        /* If option value contained in values, set selected attribute */
                        if (newParents.indexOf(value) !== -1) {
                            option.setAttribute('selected', 'selected');
                        } else {
                            option.removeAttribute('selected');
                        }
                    }

                    openModal(modal)
                    changeModalTitle(modal, '{{ __('contents.update', ['title' => ':title']) }}'.replace(':title', translations.{{ app()->getLocale() }}.title))
                })
        }

        const __sort = () => window.location.href = '{{ route('contents.sort') }}'

        /* Delete Checked */
        const __checkAll = el => {
            if (el.checked){
                document.getElementsByName('checked[]').forEach(check => {
                    check.checked = true
                })
            }else{
                document.getElementsByName('checked[]').forEach(check => {
                    check.checked = false
                })
            }
            __showDeleteCheckedButton(el.checked)
        }

        const __showDeleteCheckedButton = () => {
            const isAnyChecked = document.querySelector('input[name="checked[]"]:checked')
            const buttonEl = document.querySelector('.delete-checked')
            if (isAnyChecked && buttonEl.classList.contains('d-none')){
                buttonEl.classList.remove('d-none')
            }else if(!buttonEl.classList.contains('d-none') && !isAnyChecked){
                buttonEl.classList.add('d-none')
            }
        }

        const __deleteChecked = () => {
            const checkedIds = []
            document.querySelectorAll('input[name="checked[]"]:checked').forEach(check => {
                if (check.checked){
                    checkedIds.push(check.value)
                }
            })
            console.log(checkedIds)
        }
        /* /Delete Checked */
    </script>
@endpush
