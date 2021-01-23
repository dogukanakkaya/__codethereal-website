@extends('site.layouts.base')

@push('styles')
    <link rel="stylesheet" href="{{ asset('site/css/prism.css') }}">
@endpush

@section('content')
    <section class="page-breadcrumb">
        <nav>
            <ul class="d-flex justify-content-center align-items-center">
                <li><a href="{{ route('web.index') }}">{{ __('site.home') }}</a></li>
                @foreach($parentTree as $pTree)
                    <li><a href="{{ createUrl($pTree['url']) }}">{{ $pTree['title'] }}</a></li>
                @endforeach
                <li>{{ $content->title }}</li>
            </ul>
        </nav>
    </section>

    <section class="container mb-5">
        <div class="row">
            <div class="col-lg-9">
                <div class="banner">
                    <img src="{{ resize($content->featured_image, 1100) }}" class="w-100" alt="">
                    <div class="content-info">
                        <ul class="d-flex">
                            <li><a href="#"><i class="bi bi-pencil"></i> {{ $content->created_by_name }}</a></li>
                            <li><a href="#"><i class="bi bi-clock"></i> {{ $content->created_at->diffForHumans() }}</a></li>
                            <li><a href="#"><i class="bi bi-chat-text"></i> 8 comment</a></li>
                        </ul>
                        <ul class="d-flex">
                            <li><a href="#"><i class="bi bi-facebook facebook"></i></a></li>
                            <li><a href="#"><i class="bi bi-github github"></i></a></li>
                            <li><a href="#"><i class="bi bi-youtube youtube"></i></a></li>
                            <li><a href="#"><i class="bi bi-linkedin linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="full-content">
                    {!! $content->full !!}
                </div>
                <div class="content-tags">
                    @foreach(array_map('trim', explode(',', $content->meta_tags)) as $tag)
                        <a href="{{ createUrl('t/' . $tag) }}" title="{{ $tag }}">{{ ucfirst($tag) }}</a>
                    @endforeach
                </div>
                <div class="write-comment">
                    <h5>Leave a comment</h5>
                    <form onsubmit="return false;" class="d-flex">
                        <textarea rows="4" placeholder="Enter your comment..."></textarea>
                        <button class="ce-btn me-0" type="submit">Send <i class="bi bi-check-all"></i></button>
                    </form>
                </div>
                <div class="comments">
                    <h5>Comments (3)</h5>
                    <ul>
                        <li>
                            <div class="comment">
                                <span class="avatar">DA</span>
                                <div>
                                    <h6>Doğukan Akkaya</h6>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, ipsum.</p>
                                    <span><i class="bi bi-clock"></i> 16 hours ago</span>
                                    <span><i class="bi bi-reply ms-3"></i> Reply</span>
                                </div>
                            </div>
                            <ul>
                                <li>
                                    <div class="comment">
                                        <span class="avatar">DA</span>
                                        <div>
                                            <h6>Doğukan Akkaya</h6>
                                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, ipsum.</p>
                                            <span><i class="bi bi-clock"></i> 16 hours ago</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="comment">
                                <span class="avatar">DA</span>
                                <div>
                                    <h6>Doğukan Akkaya</h6>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, ipsum.</p>
                                    <span><i class="bi bi-clock"></i> 16 hours ago</span>
                                    <span><i class="bi bi-reply ms-3"></i> Reply</span>
                                </div>
                            </div>
                        </li>
                    </ul>
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
                        <h4 class="title">{{ __('site.relational_contents') }}</h4>
                        @foreach($relationalContents as $relationalContent)
                            <div class="recent-post">
                                <a href="{{ createUrl($relationalContent->url) }}"><img src="{{ resize($relationalContent->featured_image, 200) }}" alt=""></a>
                                <div class="info">
                                    <h4><a href="{{ createUrl($relationalContent->url) }}">{{ $relationalContent->title }}</a></h4>
                                    <span class="timestamp">{{ $relationalContent->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="item">
                        <h4 class="title">Saved Posts</h4>
                        <div class="recent-post">
                            <a href="#"><img src="{{ asset('site/img/ejs-template.gif') }}" alt=""></a>
                            <div class="info">
                                <h4><a href="#">Using EJS Template with NodeJS</a></h4>
                                <span class="timestamp">16 hours ago</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('site/js/prism.js') }}"></script>
@endpush
