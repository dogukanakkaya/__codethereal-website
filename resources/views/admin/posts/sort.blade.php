@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ce/sortable.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center  mb-4">
        <x-breadcrumb :nav="$navigations"/>
    </div>
    <div class="list-area p-4">
        <div class="sortable list-group">
            @foreach($posts as $post)
                <div class="list-group-item" data-sortable-id="{{ $post->id }}">
                    {{ $post->title }}
                </div>
            @endforeach
        </div>
        <div class="text-end mt-3">
            {{ Form::save(['onclick' => '__saveSequence()']) }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/static/sortable.min.js') }}"></script>
    <script>
        const sortable = new Sortable(document.querySelector('.sortable'), {
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            ghostClass: 'ghost',
            filter: '.dropdown',
            multiDrag: true,
            selectedClass: 'multi-selected', // The class applied to the selected items
            fallbackTolerance: 3, // So that we can select items on mobile
            dataIdAttr: "data-sortable-id"
        });

        const __saveSequence = () => {
            toggleBtnLoading()
            request.put('{{ route('posts.save_sequence') }}', sortable.toArray())
                .then(response => {
                    makeToast(response.data)
                    toggleBtnLoading()
                })
        }
    </script>
@endpush

