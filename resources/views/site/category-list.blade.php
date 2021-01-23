@extends('site.layouts.base')

@section('content')
    <section class="page-breadcrumb">
        <nav>
            <ul class="d-flex justify-content-center align-items-center">
                <li><a href="{{ route('web.index') }}">{{ __('site.home') }}</a></li>
                @foreach($parentTree as $pTree)
                    <li><a href="{{ createUrl($pTree['url']) }}">{{ $pTree['title'] }}</a></li>
                @endforeach
                <li>{{ $category->title }}</li>
            </ul>
        </nav>
    </section>

    <section class="container mb-5">
        <div class="row">
            <div class="col-lg-12">
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
                                    <span>({{ $category->childrens_count }} {{ __('site.article') }})</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
