@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ce/sortable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contents/base.css') }}">
@endpush

@section('content')
    <x-breadcrumb :nav="$navigations"/>
    <div class="list-area p-4">
        {!! buildHtmlTree($tree, [
            'start' => '<div class="list-group sortable" data-parent-id="{parentId}">',
            'end' => '</div>',

            'childStart' => '<div data-sortable-id="{value}" class="list-group-item">{title}',
            'childEnd' => '</div>',
        ], [
            'id' => 'id',
            'title' => 'title'
        ]) !!}
        <div class="text-end mt-3">
            {{ Form::save(['onclick' => '__saveSequence()']) }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/static/sortable.min.js') }}"></script>
    <script>
        const sortableGroup = '.sortable' // Sortable group selector
        const sortables = [];
        const sortableRoot = document.querySelector(sortableGroup) // First of the sortables

        const initializeSortables = () => {
            // We destroy all sortables
            sortables.forEach(n => n.destroy())

            // And then initalize again (for ajax refresh)
            document.querySelectorAll(sortableGroup).forEach(sortable => {
                sortables.push(new Sortable(sortable, {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    ghostClass: 'ghost',
                    filter: '.dropdown',
                    multiDrag: true,
                    selectedClass: 'multi-selected', // The class applied to the selected items
                    fallbackTolerance: 3, // So that we can select items on mobile
                }))
            })
        }

        initializeSortables()

        const __saveSequence = () => {
            toggleBtnLoading()
            request.put('{{ route('contents.save_sequence') }}', nestedSortableSerialize(sortableRoot, sortableGroup))
                .then(response => {
                    makeToast(response.data)
                    toggleBtnLoading()
                })
        }
    </script>
@endpush

