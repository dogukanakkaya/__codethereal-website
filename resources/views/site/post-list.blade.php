@extends('layouts.site')

@section('content')
    <section class="page-breadcrumb" style="background: linear-gradient(to right,rgba(12, 41, 116, 0.84) 0%,rgba(35, 107, 237, 0.84) 48%);">
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
                <li>{{ $category->title ?? ucfirst(__('routes.posts')) }}</li>
            </ul>
        </nav>
    </section>

    <section class="container mb-5">
        <div class="row">
            <div class="col-lg-9">
                <div class="row gy-5 posts">
                    @foreach($posts as $post)
                        <div class="col-md-6">
                            <div class="card">
                                <span class="date">{{ $post->created_at->format('d') }} {{ __('date.'.$post->created_at->format('m')) }}</span>
                                <div class="image">
                                    <a href="{{ createUrl($post->url) }}">
                                        <img src="{{ resize($post->featured_image, 750) }}" alt="">
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
            <div class="col-lg-3">
                <aside>
                    <div class="item">
                        <input type="search" placeholder="{{ __('site.search') }}...">
                    </div>
                    <div class="item">
                        <h4 class="title">{{ __('site.categories') }}</h4>
                        <ul>
                            @foreach($category_links as $category_link)
                                <li><a href="{{ createUrl($category_link->url) }}"><i class="bi bi-chevron-right"></i> {{ $category_link->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="item">
                        <h4 class="title">{{ __('site.most_viewed_posts') }}</h4>
                        @foreach($most_viewed_posts as $most_viewed_post)
                            <div class="recent-post">
                                <a href="{{ createUrl($most_viewed_post->url) }}"><img src="{{ resize($most_viewed_post->featured_image, 200) }}" alt=""></a>
                                <div class="info">
                                    <h4><a href="{{ createUrl($most_viewed_post->url) }}">{{ $most_viewed_post->title }}</a></h4>
                                    <span class="timestamp">{{ $most_viewed_post->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
