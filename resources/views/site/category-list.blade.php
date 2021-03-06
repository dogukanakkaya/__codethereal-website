@extends('site.layout')

@section('content')
    <section class="page-breadcrumb" style="background: linear-gradient(to right,rgba(12, 41, 116, 0.84) 0%,rgba(35, 107, 237, 0.84) 48%), url({{ resize($wide_image, null, 600) }});">
        <nav>
            <ul>
                <li><a href="{{ route('web.index') }}">{{ __('site.home') }}</a></li>
                @foreach($parent_tree as $tree_items)
                    <li>
                        @foreach($tree_items as $item)
                            <a href="{{ createUrl($item['url']) }}">{{ $item['title'] }}</a>
                        @endforeach
                    </li>
                @endforeach
                <li>{{ $category->title }}</li>
            </ul>
        </nav>
    </section>

    <section class="categories container">
        <div class="row">
            <div class="col-md-12 head d-flex justify-content-between align-items-center">
                <h4>{{ $category->title }}</h4>
            </div>
        </div>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-3">
                    <div class="item">
                        <a href="{{ createUrl($category->url) }}">
                            <img src="{{ resize($category->featured_image, 150) }}" alt="">
                            <h5>{{ $category->title }}</h5>
                        </a>
                        <span>({{ $category->children_count }} {{ __('site.post') }})</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
