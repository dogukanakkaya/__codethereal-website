@extends('layouts.site')

@section('content')
    <section class="page-breadcrumb" style="background: linear-gradient(to right,rgba(12, 41, 116, 0.84) 0%,rgba(35, 107, 237, 0.84) 48%), url({{ asset('site/img/home-top-bg.jpg') }});">
        <nav>
            <ul class="d-flex justify-content-center align-items-center">
                <li><a href="{{ route('web.index') }}">{{ __('site.home') }}</a></li>
                @foreach($parentTree as $pTree)
                    <li><a href="{{ createUrl($pTree['url']) }}">{{ $pTree['title'] }}</a></li>
                @endforeach
                <li>{{ $category->title ?? ucfirst(__('routes.articles')) }}</li>
            </ul>
        </nav>
    </section>

    <section class="container mb-5">
        <div class="row">
            <div class="col-lg-9">
                <div class="row gy-5 contents">
                    @foreach($contents as $content)
                        <div class="col-md-6">
                            <div class="card">
                                <span class="date">{{ $content->created_at->format('d') }} {{ __('date.'.$content->created_at->format('m')) }}</span>
                                <div class="image">
                                    <a href="{{ createUrl($content->url) }}">
                                        <img src="{{ resize($content->featured_image, 500) }}" alt="">
                                    </a>
                                    <div class="item-overlay">
                                        <a href="{{ createUrl($content->url) }}"> <i class="bi bi-link-45deg"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><a href="{{ createUrl($content->url) }}">{{ $content->title }}</a></h5>
                                    <p class="card-text">{!! $content->description !!}</p>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center card-bottom">
                                        <span><i class="bi bi-pencil"></i> {{ $content->created_by_name }}</span>
                                        <a href="{{ createUrl($content->url) }}"><i class="bi bi-chevron-double-right"> Read More</i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-md-12 mb-5">
                        {{ $contents->links('site.partials.pagination') }}
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <aside>
                    <div class="item">
                        <input type="search" placeholder="Search...">
                    </div>
                    <div class="item">
                        <h4 class="title">{{ __('site.categories') }}</h4>
                        <ul>
                            @foreach($categoryLinks as $categoryLink)
                                <li><a href="{{ createUrl($categoryLink->url) }}"><i class="bi bi-chevron-right"></i> {{ $categoryLink->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="item">
                        <h4 class="title">{{ __('site.most_viewed_contents') }}</h4>
                        @foreach($mostViewedContents as $mostViewedContent)
                            <div class="recent-post">
                                <a href="{{ createUrl($mostViewedContent->url) }}"><img src="{{ resize($mostViewedContent->featured_image, 200) }}" alt=""></a>
                                <div class="info">
                                    <h4><a href="{{ createUrl($mostViewedContent->url) }}">{{ $mostViewedContent->title }}</a></h4>
                                    <span class="timestamp">{{ $mostViewedContent->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
