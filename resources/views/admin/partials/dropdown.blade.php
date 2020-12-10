<div class="dropdown action-dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ $title ?? __('global.actions') }}
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @foreach($actions as $action)
            @isset($action['separator'])
                <div class="dropdown-divider"></div>
            @endif
            <a class="dropdown-item" onclick="{{ $action['onclick'] ?? '' }}">{!! $action['title'] !!}</a>
        @endforeach
    </div>
</div>
