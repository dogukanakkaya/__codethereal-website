<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($navigations as $url => $name)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $url }}">{{ $name }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
