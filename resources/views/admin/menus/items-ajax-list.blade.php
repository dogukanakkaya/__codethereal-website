@php
    $dropdown = view()->make('admin.partials.single-actions', ['actions' => $actions]);
@endphp
{!! buildHtmlTree($items, [
    'start' => '<div class="list-group sortable" data-parent-id="{parentId}">',
    'end' => '</div>',

    'childStart' => '<div data-sortable-id="{value}" class="list-group-item">{title} ' . $dropdown,
    'childEnd' => '</div>',
], [
    'id' => 'item_id',
    'title' => 'title'
]) !!}
