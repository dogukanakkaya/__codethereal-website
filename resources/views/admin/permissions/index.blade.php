@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/permissions/base.css') }}">
@endpush

@section('content')
    <x-breadcrumb :nav="$navigations"/>
    <div class="page-actions">
    <!--<input class="ce-input" type="search" placeholder="{{ __('global.search') }}...">-->
        <button class="btn btn-primary" onclick="ajaxList()">{{ __('global.refresh') }} <i
                class="material-icons-outlined md-18 spin">sync</i></button>
        <button onclick="__create()"
                class="btn btn-success">{{ __('global.add_new', ['name' => __('permissions.permission')]) }}
            <i class="material-icons-outlined md-18">add</i></button>
    </div>
    <div id="list" class="list-area p-4">
        @include('admin.permissions.ajax-list', ['permissionGroups' => $permissionGroups])
    </div>

    @include('admin.permissions.form-modal')
@endsection

@push('scripts')
    <script>
        let updateId = 0;
        const form = document.getElementById('permission-form')
        const modal = '#permission-form-modal'

        const ajaxList = () => {
            request.get('{{ route('permissions.ajax') }}')
                .then(response => document.getElementById('list').innerHTML = response.data)
        }

        const __onResponse = response => {
            makeToast(response.data)
            if (response.data.status) {
                closeModal(modal)
                form.reset()
                ajaxList()
            }
            toggleBtnLoading()
        }

        form.addEventListener('submit', e => {
            e.preventDefault()
            toggleBtnLoading()
            const formData = serialize(form, {hash: true})
            if (updateId > 0) {
                const url = '{{ route('permissions.update', ['id' => ':id']) }}'.replace(':id', updateId)
                request.put(url, formData)
                    .then(__onResponse)
            } else {
                request.post('{{ route('permissions.create') }}', formData)
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
            if (confirm('{{ __('global.confirm_delete') }}')) {
                const url = '{{ route('permissions.destroy', ['id' => ':id']) }}'.replace(':id', id)
                request.delete(url)
                    .then(__onResponse)
            }
        }

        const __find = id => {
            updateId = id
            const url = '{{ route('permissions.find', ['id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(response => {
                    for (const [key, value] of Object.entries(response.data)) {
                        document.querySelector(`input[name=${key}]`).value = value
                    }
                    openModal(modal)
                })
        }
    </script>
@endpush
