@php
    $dropdown = View::make('admin.partials.dropdown', [
        'actions' => [
            ['title' => '<i class="fas fa-pencil-alt fa-fw"></i> ' . __('global.update'), 'onclick' => '__find({value})'],
            ['title' => '<i class="fas fa-trash fa-fw"></i> ' . __('global.delete'), 'onclick' => '__delete({value})']
        ]
    ]);
@endphp
{!! buildHtmlTree($items, [
    'start' => '<div class="list-group sortable" data-parent-id="{parentId}">',
    'end' => '</div>',

    'childStart' => '<div data-sortable-id="{value}" class="list-group-item">{title}' . $dropdown,
    'childEnd' => '</div>',
], [
    'id' => 'item_id',
    'title' => 'title'
]) !!}
