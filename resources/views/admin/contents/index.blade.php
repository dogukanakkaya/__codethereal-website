@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contents/base.css') }}">
@endpush

@section('content')
    <x-breadcrumb :nav="$navigations"/>
    <div class="page-actions">
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
        const form = document.getElementById('content-form')
        const modal = '#content-form-modal'

        const __create = () => {
            openModal(modal)
        }

        const __onResponse = response => {
            makeToast(response.data)
            if (response.data.status) {
                form.reset()
            }
            toggleBtnLoading()
        }

        form.addEventListener('submit', e => {
            e.preventDefault()
            toggleBtnLoading()
            const formData = serialize(form, {hash: true, empty: true})
            request.post('{{ route('contents.create') }}', formData)
                .then(__onResponse)
        })
    </script>
@endpush
