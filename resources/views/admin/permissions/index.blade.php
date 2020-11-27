@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/permissions/base.css') }}">
@endpush

@section('content')
    <x-breadcrumb :nav="$navigations"/>
    <div class="permissions">
        <div class="pb-3 d-flex justify-content-end">
            <div>
                <!--<input class="ce-input" type="search" placeholder="{{ __('global.search') }}...">-->
                    <button class="btn btn-primary" onclick="ajaxList()">{{ __('global.refresh') }} <i class="fas fa-sync fa-spin"></i></button>
                <button onclick="__create()"
                        class="btn btn-success">{{ __('global.add_new', ['name' => __('permissions.permission')]) }}
                    <i class="fas fa-plus"></i></button>
            </div>
        </div>
        <div id="list" class="list-area p-4">
            @include('admin.permissions.ajax-list', ['permissionGroups' => $permissionGroups])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let updateId = 0;
        const form = document.getElementById('permission-form')

        const ajaxList = () => {
            request.get('{{ route('permissions.ajax') }}')
                .then(response => document.getElementById('list').innerHTML = response.data)
        }

        const __onResponse = response => {
            makeToast(response.data)
            if(response.data.status){
                $("#permissionFormModal").modal('hide') // TODO: jquery to pure js
                form.reset()
                ajaxList()
            }
            toggleBtnLoading()
        }

        form.addEventListener('submit', e => {
            e.preventDefault()
            toggleBtnLoading()
            const formData = serialize(form, { hash: true })
            if(updateId > 0){
                const url = '{{ route('permissions.update', ['id' => ':id']) }}'.replace(':id', updateId)
                request.put(url, formData)
                    .then(__onResponse)
            }else{
                request.post('{{ route('permissions.create') }}', formData)
                    .then(__onResponse)
            }

        })

        const __create = () => {
            if(updateId > 0){
                form.reset()
                updateId = 0;
            }
            $("#permissionFormModal").modal('show') // TODO: jquery to pure js
        }

        const __delete = id => {
            if(confirm('{{ __('global.confirm_delete') }}')){
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
                    for(const [key, value] of Object.entries(response.data)){
                        document.querySelector(`input[name=${key}]`).value = value
                    }
                    $("#permissionFormModal").modal('show') // TODO: jquery to pure js
                })
        }
    </script>
@endpush

@include('admin.permissions.form-modal')
