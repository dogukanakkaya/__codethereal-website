@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/config/base.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <x-breadcrumb :nav="$navigations"/>
    </div>
    <div id="list" class="list-area p-4">
        {!! $dirTree !!}
    </div>
    @include('admin.config.form-modal')
@endsection

@push('scripts')
    <script>
        let path = '';
        const form = document.getElementById('config-form')
        const modal = '#config-form-modal'

        const __onResponse = response => {
            makeToast(response.data)
            if (response.data.status) {
                closeModal(modal)
                form.reset()
            }
            toggleBtnLoading()
        }

        form.addEventListener('submit', e => {
            e.preventDefault()
            toggleBtnLoading()
            const formData = serialize(form, {hash: true})
            formData.path = path
            request.put('{{ route('config.update') }}', formData)
                .then(__onResponse)

        })

        const __find = _path => {
            path = _path
            request.post('{{ route('config.find') }}', {path})
                .then(response => {
                    openModal(modal)
                    document.querySelector('textarea[name="content"]').value = response.data.content
                })
        }
    </script>
@endpush
