@extends('layouts.site')

@section('content')
    <section class="page-breadcrumb" style="background: linear-gradient(to right,rgba(12, 41, 116, 0.84) 0%,rgba(35, 107, 237, 0.84) 48%), url({{ asset('site/img/home-top-bg.jpg') }});">
        <nav>
            <ul class="d-flex justify-content-center align-items-center">
                <li><a href="{{ route('web.index') }}">{{ __('site.home') }}</a></li>
                @isset($category)
                <li><a href="{{ createUrl($category->url) }}">{{ $category->title }}</a></li>
                @endisset
                <li>{{ $search }}</li>
            </ul>
        </nav>
    </section>

    <section class="container mb-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="row gy-5 posts">
                    @foreach($posts as $post)
                        <div class="col-md-4">
                            <div class="card">
                                <span class="date">{{ $post->created_at->format('d') }} {{ __('date.'.$post->created_at->format('m')) }}</span>
                                <div class="image">
                                    <a href="{{ createUrl($post->url) }}">
                                        <img src="{{ resize($post->featured_image, 500) }}" alt="">
                                    </a>
                                    <div class="item-overlay">
                                        <a href="{{ createUrl($post->url) }}"> <i class="bi bi-link-45deg"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><a href="{{ createUrl($post->url) }}">{{ $post->title }}</a></h5>
                                    <p class="card-text">{!! $post->description !!}</p>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center card-bottom">
                                        <span><i class="bi bi-pencil"></i> {{ $post->created_by_name }}</span>
                                        <a href="{{ createUrl($post->url) }}"><i class="bi bi-chevron-double-right"> {{ __('site.read_more') }}</i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-md-12 mb-5">
                        {{ $posts->links('site.partials.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
