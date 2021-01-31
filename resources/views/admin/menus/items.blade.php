@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ce/sortable.css') }}">
    <link rel="stylesheet" href="{{ asset('site/bootstrap-icons/bootstrap-icons.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <x-breadcrumb :nav="$navigations"/>
        <div class="page-actions">
            {{ Form::refresh(['onclick' => 'ajaxList()']) }}
            @can('create_menus')
            {{ Form::addNew(['onclick' => '__create()']) }}
            @endcan
        </div>
    </div>
    <div class="list-area p-4">
        @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])
        <div id="list">
            @include('admin.menus.items-ajax-list', ['items' => $items])
        </div>
        <div class="text-end mt-3">
            {{ Form::save(['onclick' => '__saveSequence()']) }}
        </div>
    </div>

    @include('admin.menus.items-form-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/static/sortable.min.js') }}"></script>
    <script src="{{ asset('js/ce/biconpicker.js') }}"></script>
    <script>
        let updateId = 0;
        const form = document.getElementById('menu-item-form')
        const modal = '#menu-item-form-modal'

        const sortableGroup = '.sortable' // Sortable group selector
        let sortables = [];
        const sortableRoot = document.querySelector(sortableGroup) // First of the sortables

        const initializeSortables = () => {
            // We destroy all sortables
            sortables.forEach(n => n.destroy())
            sortables = []

            // And then initialize again
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

        const ajaxList = () => {
            request.get('{{ route('menu_items.ajax', ['groupId' => $groupId]) }}')
                .then(response => {
                    document.getElementById('list').innerHTML = response.data
                    initializeSortables()
                })
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
            const formData = serialize(form, {hash: true, empty: true})
            if (updateId > 0) {
                const url = '{{ route('menu_items.update', ['groupId' => $groupId, 'id' => ':id']) }}'.replace(':id', updateId)
                request.put(url, formData)
                    .then(__onResponse)
            } else {
                request.post('{{ route('menu_items.create', ['groupId' => $groupId]) }}', formData)
                    .then(__onResponse)
            }
        })

        const __create = () => {
            if (updateId > 0) {
                form.reset()
                updateId = 0;
            }
            openModal(modal)
            changeModalTitle(modal, '{{ __('menus.item.add_new') }}')
        }

        const __delete = id => {
            if (confirm('{{ __('messages.confirmation.delete.custom_message') }}')) {
                const url = '{{ route('menu_items.destroy', ['groupId' => $groupId, 'id' => ':id']) }}'.replace(':id', id)
                request.delete(url)
                    .then(res => {
                        res.data.addition = `<a href="javascript:void(0);" onclick="__undoDelete(${id})">{{ __('buttons.undo') }}</a>`;
                        __onResponse(res)
                    })
            }
        }

        const __undoDelete = id => {
            const url = '{{ route('menu_items.restore', ['groupId' => $groupId, 'id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(__onResponse)
        }

        const __find = id => {
            updateId = id
            const url = '{{ route('menu_items.update', ['groupId' => $groupId, 'id' => ':id']) }}'.replace(':id', id)
            request.get(url)
                .then(response => {
                    const {item, translations} = response.data

                    document.querySelector(`select[name="item[parent_id]"]`).value = item.parent_id

                    for(const [language, values] of Object.entries(translations)){
                        document.querySelector(`input[name="${language}[title]"]`).value = values.title ?? ''
                        document.querySelector(`input[name="${language}[url]"]`).value = values.url ?? ''
                        document.querySelector(`input[name="${language}[icon]"]`).value = values.icon ?? ''
                        document.querySelector(`input[name="${language}[icon]"]`).dispatchEvent(new Event('change'))
                        document.querySelector(`input[type=checkbox][name="${language}[active]"]`).checked = parseInt(values.active) === 1
                    }

                    openModal(modal)
                    changeModalTitle(modal, '{{ __('menus.item.update', ['title' => ':title']) }}'.replace(':title', translations.{{ app()->getLocale() }}.title))
                })
        }

        const __saveSequence = () => {
            toggleBtnLoading()
            request.put('{{ route('menu_items.save_sequence', ['groupId' => $groupId]) }}', nestedSortableSerialize(sortableRoot, sortableGroup))
                .then(__onResponse)
        }

        @foreach($languages as $language)
            new Biconpicker(document.querySelector(`input[name="{{ $language->code }}[icon]"]`), {
                showSelectedIn: document.querySelector('.selected-bicon-{{ $language->code }}')
            })
        @endforeach

    </script>
@endpush
