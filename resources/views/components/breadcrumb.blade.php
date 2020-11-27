<nav class="ce-breadcrumb">
    <ul>
        @foreach ($navigations as $url => $name)
            @if ($loop->last)
                <li><a href="javascript:void(0);">{{ $name }}</a></li>
            @else
                <li><a href="{{ $url }}">{{ $name }}</a></li>
            @endif
        @endforeach
    </ul>
</nav>
