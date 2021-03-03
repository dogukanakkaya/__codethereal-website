@extends('layouts.site')

@section('content')
    <section class="home-top" style="background: linear-gradient(to right,rgba(12, 41, 116, 0.84) 0%,rgba(35, 107, 237, 0.84) 48%), url({{ resize($home_top->featured_image ?? '', null, 1200) }}) no-repeat;">
        <div class="center container">
            <div>
                <h3 class="slogan"><span>{{ $home_top->title ?? '' }}</span></h3>
            </div>
            <div class="d-flex justify-content-center align-items-center flex-lg-row flex-column">
                <search search-trans="{{ __('site.search') }}..." search-route="{{ route('web.search', ['q' => ':q']) }}" item-route="{{ createUrl() }}"></search>
            </div>
        </div>
    </section>

    @isset($category)
    <section class="categories container">
        <div class="row">
            <div class="col-md-12 head d-flex justify-content-between align-items-center">
                <h4>{{ $category->title }}</h4>
                <a href="{{ createUrl($category->url) }}" class="ce-btn"><i class="bi bi-list"></i> {{ __('site.show_all') }}</a>
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
    @endisset

    @isset($parallax)
    <section class="separate-parallax" style="background-image: url('{{ resize($parallax->featured_image, null, 500) }}');">
        <div class="overlay flex-column d-flex justify-content-center align-items-center p-3 text-center">
            <h3>{{ $parallax->title }}</h3>
            <p>{{ $parallax->description }}</p>
            @guest
                <span class="ce-btn" onclick="__register()"><i class="bi bi-person-plus"></i> {{ __('site.auth.register') }}</span>
            @endguest
        </div>
    </section>
    @endisset

    <section class="container posts">
        <div class="row">
            <div class="col-md-12">
                <div class="position-relative">
                    <div class="head d-flex justify-content-between align-items-center">
                        <h4>{{ __('site.featured_posts') }}</h4>
                        <a href="{{ createUrl(__('routes.posts')) }}" class="ce-btn"><i class="bi bi-list"></i> {{ __('site.show_all') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($featured_posts as $featured_post)
                <div class="col-md-4">
                    <div class="card">
                        <span class="date">{{ $featured_post->created_at->format('d') }} {{ __('date.'.$featured_post->created_at->format('m')) }}</span>
                        <div class="image">
                            <a href="{{ createUrl($featured_post->url) }}">
                                <img src="{{ resize($featured_post->featured_image, 750) }}" alt="">
                            </a>
                            <div class="item-overlay">
                                <a href="{{ createUrl($featured_post->url) }}"> <i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ createUrl($featured_post->url) }}">{{ $featured_post->title }}</a></h5>
                            <p class="card-text">{!! $featured_post->description !!}</p>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center card-bottom">
                                <span><i class="bi bi-pencil"></i> {{ $featured_post->created_by_name }}</span>
                                <span><i class="bi bi-eye"></i> {{ $featured_post->views }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="separate-parallax mt-5 mb-5" style="background-image: url('{{ resize($parallax->featured_image ?? '', null, 500) }}');">
        <div class="overlay flex-column d-flex justify-content-center align-items-center">
            <ul class="d-flex justify-content-evenly w-100 counters">
                <li>
                    <i class="bi bi-person-check"></i>
                    <h4>{{ $user_count }}+</h4>
                    <h4>{{ __('site.user') }}</h4>
                </li>
                <li>
                    <i class="bi bi-bookmark-check"></i>
                    <h4>{{ $category_children_sum }}+</h4>
                    <h4>{{ __('site.post') }}</h4>
                </li>
                <li>
                    <i class="bi bi-book"></i>
                    <h4>{{ $article_count }}+</h4>
                    <h4>{{ __('site.article') }}</h4>
                </li>
                <li>
                    <i class="bi bi-collection"></i>
                    <h4>{{ $category_count }}+</h4>
                    <h4>{{ __('site.category') }}</h4>
                </li>
            </ul>
        </div>
    </section>

    <section class="container mb-5">
        <div class="row">
            @foreach($articles as $article)
                <div class="col-md-4">
                    <div class="ce-card">
                        <div class="face face1">
                            <div class="content">
                                <img src="{{ resize($article->featured_image, 200) }}" alt="{{ $article->title }}">
                                <h3>{{ $article->title }}</h3>
                            </div>
                        </div>
                        <div class="face face2">
                            <div class="content">
                                <p>{{ $article->description }}</p>
                                <a href="{{ createUrl($article->url) }}" class="ce-btn">{{ __('site.read_more') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{--
    <section class="container mb-5 job-posts">
        <div class="row">
            <div class="col-12 section-title">
                <h2>Job Posts</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel distinctio aut esse ipsa atque natus?</p>
                <div class="line"></div>
            </div>
            <div class="col-12">
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="logo">
                            <img src="{{ asset('site/img/logo-dark.svg') }}" alt="">
                        </td>
                        <td class="title">
                            <h5><a href="#">Software Developer</a></h5>
                            <span>Codethereal</span>
                        </td>
                        <td class="location">
                            <i class="bi bi-geo-alt"></i> Location
                        </td>
                        <td class="type">
                            <span>BUTTON</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    --}}
@endsection
