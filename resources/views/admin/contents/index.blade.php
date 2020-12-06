@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contents/base.css') }}">
@endpush

@section('content')
    <x-breadcrumb :nav="$navigations"/>
    <div class="page-actions">
        {{ Form::refresh(['onclick' => '__refresh()']) }}
        <button class="btn btn-success" onclick="window.location.href = '{{ route('contents.create') }}'">{{ __('global.add_new', ['name' => __('contents.content')]) }} <i
                class="material-icons-outlined md-18">add</i></button>
    </div>
    <div class="list-area p-4">
        @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])
        <x-datatable :url="route('contents.datatable')" :columns="$columns"/>
    </div>
@endsection

@push('scripts')
    <script>

    </script>
@endpush
