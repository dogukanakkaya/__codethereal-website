<nav aria-label="breadcrumb" class="ce-breadcrumb mb-4">
    <ul class="d-flex align-items-center">
        @foreach ($navigations as $url => $name)
            @if ($loop->last)
                <li class="active" aria-current="page">{{ $name }}</li>
            @else
                <li><a href="{{ $url }}">{{ $name }}</a></li>
            @endif
        @endforeach
    </ul>
</nav>

