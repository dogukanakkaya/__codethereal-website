@extends('admin.layouts.base')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <x-breadcrumb :nav="$navigations"/>
        <div class="page-actions">
            {{ Form::refresh(['onclick' => '__refresh()']) }}
            {{ Form::addNew(['onclick' => '__create()']) }}
        </div>
    </div>
    <div class="list-area p-4">
        @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])
        <x-datatable :url="route('menus.datatable')" :columns="$columns"/>
    </div>

    @include('admin.menus.form-modal')
@endsection

@push('scripts')
    <script>
        let updateId = 0;
        const form = document.getElementById('menu-group-form')
        const modal = '#menu-group-form-modal'

        const __onResponse = response => {
            makeToast(response.data)
            if (response.data.status) {
                closeModal(modal)
                form.reset()
                __refresh()
            }
            toggleBtnLoading()
        }

        form.addEventListener('submit', e => {
            e.preventDefault()
            toggleBtnLoading()
            const formData = serialize(form, {hash: true})
            if (updateId > 0) {
                const url = '{{ route('menus.update', ['id' => ':id']) }}'.replace(':id', updateId)
                request.put(url, formData)
                    .then(__onResponse)
            } else {
                request.post('{{ route('menus.create') }}', formData)
                    .then(__onResponse)
            }
        })

        const __create = () => {
            if (updateId > 0) {
                form.reset()
                updateId = 0;
            }
            openModal(modal)
        }

        const __delete = id => {
            if (confirm('{{ __('messages.confirmation.delete.custom_message') }}')) {
                const url = '{{ route('menus.destroy', ['id' => ':id']) }}'.replace(':id', id)
                request.delete(url)
                    .then(res => {
                        res.data.addition = `<a href="javascript:void(0);" onclick="__undoDelete(${id})">{{ __('buttons.undo') }}</a>`;
                        __onResponse(res)
                    })
            }
        }

        const __undoDelete = id => {
            const url = '{{ route('menus.restore', ['id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(__onResponse)
        }

        const __find = id => {
            updateId = id
            const url = '{{ route('menus.find', ['id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(response => {
                    document.querySelector('input[name=title]').value = response.data.title
                    openModal(modal)
                })

        }
    </script>
@endpush
