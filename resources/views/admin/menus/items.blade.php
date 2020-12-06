@extends('admin.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ce/sortable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menus/base.css') }}">
@endpush

@section('content')
    <x-breadcrumb :nav="$navigations"/>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="ajaxList()">{{ __('global.refresh') }} <i
                class="material-icons-outlined md-18 spin">sync</i></button>
        <button onclick="__create()"
                class="btn btn-success">{{ __('global.add_new', ['name' => __('menus.item')]) }}
            <i class="material-icons-outlined md-18">add</i></button>
    </div>
    <div class="list-area p-4">
        @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])
        <div id="list">
            @include('admin.menus.items-ajax-list', ['items' => $items])
        </div>
        <div class="text-right mt-3">
            {{ Form::save(['onclick' => '__saveSequence()']) }}
        </div>
    </div>

    @include('admin.menus.items-form-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/static/sortable.min.js') }}"></script>
    <script>
        let updateId = 0;
        const form = document.getElementById('menu-item-form')
        const modal = '#menu-item-form-modal'

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
            const formData = serialize(form, {hash: true})
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
        }

        const __delete = id => {
            if (confirm('{{ __('global.confirm_delete') }}')) {
                const url = '{{ route('menu_items.destroy', ['groupId' => $groupId, 'id' => ':id']) }}'.replace(':id', id)
                request.delete(url)
                    .then(res => {
                        res.data.addition = `<a href="javascript:void(0);" onclick="__undoDelete(${id})">{{ __('global.undo') }}</a>`;
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

                    // TODO: think that, which one is better performance? Or maybe merge all languages foreach to one, and assign these to variables
                    let translation = {}
                    @foreach($languages as $language)
                        translation = translations?.{{ $language->code }}
                    document.querySelector(`input[name="{{ $language->code }}[title]"]`).value = translation?.title ?? ''
                    document.querySelector(`input[name="{{ $language->code }}[url]"]`).value = translation?.url ?? ''
                    document.querySelector(`input[name="{{ $language->code }}[icon]"]`).value = translation?.icon ?? ''
                    document.querySelector(`input[type=checkbox][name="{{ $language->code }}[active]"]`).checked = parseInt(translation?.active ?? 0) === 1
                    @endforeach

                    /*
                    for(const [language, values] of Object.entries(translations)){
                        document.querySelector(`input[name="${language}[title]"]`).value = values.title
                        document.querySelector(`input[name="${language}[url]"]`).value = values.url
                        document.querySelector(`input[name="${language}[icon]"]`).value = values.icon
                        document.querySelector(`input[type=checkbox][name="${language}[active]"]`).checked = parseInt(values.active) === 1
                    }
                    */

                    openModal(modal)
                })
        }

        const __saveSequence = () => {
            toggleBtnLoading()
            request.put('{{ route('menu_items.save_sequence', ['groupId' => $groupId]) }}', nestedSortableSerialize(sortableRoot, sortableGroup))
                .then(__onResponse)
        }
    </script>
@endpush
